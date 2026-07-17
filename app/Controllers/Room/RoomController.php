<?php

namespace App\Controllers\Room;

use App\Controllers\BaseController;
use App\Services\Room\RoomService;

class RoomController extends BaseController
{
    private RoomService $roomService;

    public function __construct()
    {
        $this->roomService = new RoomService();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $isActive = $this->request->getGet('is_active');
        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('per_page') ?: 20;

        $active = null;
        if ($isActive !== null && $isActive !== '') {
            $active = $isActive === '1';
        }

        $result = $this->roomService->getAll($search, $active, $page, $perPage);
        $stats = $this->roomService->getStats();

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data kamar berhasil diambil',
                'data' => $result['data'],
                'meta' => $result['meta'],
                'stats' => $stats,
            ]);
        }

        return view('rooms/index', [
            'rooms' => $result['data'],
            'meta' => $result['meta'],
            'stats' => $stats,
            'filters' => [
                'search' => $search,
                'is_active' => $isActive,
            ],
        ]);
    }

    public function create()
    {
        $roomTypes = $this->roomService->getRoomTypes();

        return view('rooms/create', [
            'roomTypes' => $roomTypes,
        ]);
    }

    public function store()
    {
        $rules = [
            'room_type_id' => 'required',
            'room_number'  => 'required|max_length[20]|is_unique[rooms.room_number]',
            'floor'        => 'integer',
            'capacity'     => 'required|integer|greater_than[0]',
            'notes'        => 'max_length[5000]',
        ];

        if (!$this->validate($rules)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'room_type_id' => $this->request->getPost('room_type_id'),
            'room_number'  => strtoupper($this->request->getPost('room_number')),
            'floor'        => $this->request->getPost('floor') ?: null,
            'capacity'     => $this->request->getPost('capacity'),
            'notes'        => $this->request->getPost('notes'),
            'is_active'    => 1,
        ];

        $room = $this->roomService->create($data);

        if ($room === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data kamar',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data kamar');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data kamar berhasil disimpan',
                'data' => $room,
            ])->setStatusCode(201);
        }

        return redirect()->to('/rooms')->with('success', 'Data kamar berhasil disimpan');
    }

    public function show(string $uuid)
    {
        $room = $this->roomService->getByUuid($uuid);

        if ($room === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Kamar tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->to('/rooms')->with('error', 'Kamar tidak ditemukan');
        }

        $beds = $this->roomService->getBeds($uuid);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data kamar berhasil diambil',
                'data' => $room,
                'beds' => $beds,
            ]);
        }

        return view('rooms/show', [
            'room' => $room,
            'beds' => $beds,
        ]);
    }

    public function edit(string $uuid)
    {
        $room = $this->roomService->getByUuid($uuid);

        if ($room === null) {
            return redirect()->to('/rooms')->with('error', 'Kamar tidak ditemukan');
        }

        $roomTypes = $this->roomService->getRoomTypes();

        return view('rooms/edit', [
            'room' => $room,
            'roomTypes' => $roomTypes,
        ]);
    }

    public function update(string $uuid)
    {
        $rules = [
            'room_type_id' => 'required',
            'room_number'  => 'required|max_length[20]',
            'floor'        => 'integer',
            'capacity'     => 'required|integer|greater_than[0]',
            'notes'        => 'max_length[5000]',
        ];

        if (!$this->validate($rules)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'room_type_id' => $this->request->getPost('room_type_id'),
            'room_number'  => strtoupper($this->request->getPost('room_number')),
            'floor'        => $this->request->getPost('floor') ?: null,
            'capacity'     => $this->request->getPost('capacity'),
            'notes'        => $this->request->getPost('notes'),
        ];

        $result = $this->roomService->update($uuid, $data);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupdate data kamar',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data kamar');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data kamar berhasil diupdate',
            ]);
        }

        return redirect()->to('/rooms/' . $uuid)->with('success', 'Data kamar berhasil diupdate');
    }

    public function delete(string $uuid)
    {
        $result = $this->roomService->delete($uuid);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus data kamar',
                ])->setStatusCode(500);
            }

            return redirect()->to('/rooms')->with('error', 'Gagal menghapus data kamar');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data kamar berhasil dihapus',
            ]);
        }

        return redirect()->to('/rooms')->with('success', 'Data kamar berhasil dihapus');
    }

    public function availability(string $uuid)
    {
        $availability = $this->roomService->getAvailability($uuid);

        if ($availability === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Kamar tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->to('/rooms')->with('error', 'Kamar tidak ditemukan');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data ketersediaan kamar berhasil diambil',
                'data' => $availability,
            ]);
        }

        return view('rooms/availability', [
            'availability' => $availability,
        ]);
    }

    private function isApiRequest(): bool
    {
        $accept = $this->request->getHeaderLine('Accept');
        $contentType = $this->request->getHeaderLine('Content-Type');

        return str_contains($accept, 'application/json') || str_contains($contentType, 'application/json');
    }
}
