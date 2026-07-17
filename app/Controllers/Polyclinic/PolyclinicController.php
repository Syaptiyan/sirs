<?php

namespace App\Controllers\Polyclinic;

use App\Controllers\BaseController;
use App\Services\Polyclinic\PolyclinicService;

class PolyclinicController extends BaseController
{
    private PolyclinicService $polyclinicService;

    public function __construct()
    {
        $this->polyclinicService = new PolyclinicService();
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

        $result = $this->polyclinicService->getAll($search, $active, $page, $perPage);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data poliklinik berhasil diambil',
                'data' => $result['data'],
                'meta' => $result['meta'],
            ]);
        }

        return view('polyclinics/index', [
            'polyclinics' => $result['data'],
            'meta' => $result['meta'],
            'filters' => [
                'search' => $search,
                'is_active' => $isActive,
            ],
        ]);
    }

    public function create()
    {
        return view('polyclinics/create');
    }

    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[2]|max_length[100]',
            'code'        => 'required|max_length[20]|is_unique[polyclinics.code]',
            'description' => 'max_length[5000]',
            'location'    => 'max_length[200]',
            'daily_quota' => 'integer|greater_than[0]',
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
            'name'        => $this->request->getPost('name'),
            'code'        => strtoupper($this->request->getPost('code')),
            'description' => $this->request->getPost('description'),
            'location'    => $this->request->getPost('location'),
            'daily_quota' => $this->request->getPost('daily_quota') ?: 20,
            'is_active'   => 1,
        ];

        $polyclinic = $this->polyclinicService->create($data);

        if ($polyclinic === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data poliklinik',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data poliklinik');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data poliklinik berhasil disimpan',
                'data' => $polyclinic,
            ])->setStatusCode(201);
        }

        return redirect()->to('/polyclinics')->with('success', 'Data poliklinik berhasil disimpan');
    }

    public function show(string $uuid)
    {
        $polyclinic = $this->polyclinicService->getByUuid($uuid);

        if ($polyclinic === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Poliklinik tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->to('/polyclinics')->with('error', 'Poliklinik tidak ditemukan');
        }

        $doctors = $this->polyclinicService->getDoctors($uuid);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data poliklinik berhasil diambil',
                'data' => $polyclinic,
                'doctors' => $doctors,
            ]);
        }

        return view('polyclinics/show', [
            'polyclinic' => $polyclinic,
            'doctors' => $doctors,
        ]);
    }

    public function edit(string $uuid)
    {
        $polyclinic = $this->polyclinicService->getByUuid($uuid);

        if ($polyclinic === null) {
            return redirect()->to('/polyclinics')->with('error', 'Poliklinik tidak ditemukan');
        }

        return view('polyclinics/edit', [
            'polyclinic' => $polyclinic,
        ]);
    }

    public function update(string $uuid)
    {
        $rules = [
            'name'        => 'required|min_length[2]|max_length[100]',
            'code'        => 'required|max_length[20]',
            'description' => 'max_length[5000]',
            'location'    => 'max_length[200]',
            'daily_quota' => 'integer|greater_than[0]',
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
            'name'        => $this->request->getPost('name'),
            'code'        => strtoupper($this->request->getPost('code')),
            'description' => $this->request->getPost('description'),
            'location'    => $this->request->getPost('location'),
            'daily_quota' => $this->request->getPost('daily_quota') ?: 20,
        ];

        $result = $this->polyclinicService->update($uuid, $data);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupdate data poliklinik',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data poliklinik');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data poliklinik berhasil diupdate',
            ]);
        }

        return redirect()->to('/polyclinics/' . $uuid)->with('success', 'Data poliklinik berhasil diupdate');
    }

    public function delete(string $uuid)
    {
        $result = $this->polyclinicService->delete($uuid);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus data poliklinik',
                ])->setStatusCode(500);
            }

            return redirect()->to('/polyclinics')->with('error', 'Gagal menghapus data poliklinik');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data poliklinik berhasil dihapus',
            ]);
        }

        return redirect()->to('/polyclinics')->with('success', 'Data poliklinik berhasil dihapus');
    }

    public function assignDoctor(string $uuid)
    {
        $rules = [
            'doctor_id' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }

            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $doctorId = (int) $this->request->getPost('doctor_id');
        $result = $this->polyclinicService->assignDoctor($uuid, $doctorId);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal assign dokter ke poliklinik',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Gagal assign dokter ke poliklinik');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Dokter berhasil diassign ke poliklinik',
            ])->setStatusCode(201);
        }

        return redirect()->back()->with('success', 'Dokter berhasil diassign ke poliklinik');
    }

    public function removeDoctor(string $uuid, int $doctorId)
    {
        $result = $this->polyclinicService->removeDoctor($uuid, $doctorId);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus dokter dari poliklinik',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Gagal menghapus dokter dari poliklinik');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Dokter berhasil dihapus dari poliklinik',
            ]);
        }

        return redirect()->back()->with('success', 'Dokter berhasil dihapus dari poliklinik');
    }

    private function isApiRequest(): bool
    {
        $accept = $this->request->getHeaderLine('Accept');
        $contentType = $this->request->getHeaderLine('Content-Type');

        return str_contains($accept, 'application/json') || str_contains($contentType, 'application/json');
    }
}