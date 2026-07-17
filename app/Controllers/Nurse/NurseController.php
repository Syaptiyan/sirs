<?php

namespace App\Controllers\Nurse;

use App\Controllers\BaseController;
use App\Services\Nurse\NurseService;

class NurseController extends BaseController
{
    private NurseService $nurseService;

    public function __construct()
    {
        $this->nurseService = new NurseService();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('per_page') ?: 20;

        $result = $this->nurseService->getAll($search, $page, $perPage);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data perawat berhasil diambil',
                'data' => $result['data'],
                'meta' => $result['meta'],
            ]);
        }

        return view('pages/nurses/index', [
            'nurses' => $result['data'],
            'meta' => $result['meta'],
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create()
    {
        return view('pages/nurses/create');
    }

    public function store()
    {
        $rules = [
            'employee_id' => 'required|max_length[20]|is_unique[nurses.employee_id]',
            'name'        => 'required|min_length[3]|max_length[150]',
            'sip'         => 'max_length[50]',
            'phone'       => 'max_length[20]',
            'email'       => 'valid_email|max_length[150]',
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
            'user_id'     => $this->request->getPost('user_id'),
            'employee_id' => $this->request->getPost('employee_id'),
            'name'        => $this->request->getPost('name'),
            'sip'         => $this->request->getPost('sip'),
            'phone'       => $this->request->getPost('phone'),
            'email'       => $this->request->getPost('email'),
            'is_active'   => 1,
        ];

        $nurse = $this->nurseService->create($data);

        if ($nurse === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data perawat',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data perawat');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data perawat berhasil disimpan',
                'data' => $nurse,
            ])->setStatusCode(201);
        }

        return redirect()->to('/nurses')->with('success', 'Data perawat berhasil disimpan');
    }

    public function show(string $uuid)
    {
        $nurse = $this->nurseService->getByUuid($uuid);

        if ($nurse === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Perawat tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->to('/nurses')->with('error', 'Perawat tidak ditemukan');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data perawat berhasil diambil',
                'data' => $nurse,
            ]);
        }

        return view('pages/nurses/show', ['nurse' => $nurse]);
    }

    public function edit(string $uuid)
    {
        $nurse = $this->nurseService->getByUuid($uuid);

        if ($nurse === null) {
            return redirect()->to('/nurses')->with('error', 'Perawat tidak ditemukan');
        }

        return view('pages/nurses/edit', [
            'nurse' => $nurse,
        ]);
    }

    public function update(string $uuid)
    {
        $rules = [
            'employee_id' => 'required|max_length[20]',
            'name'        => 'required|min_length[3]|max_length[150]',
            'sip'         => 'max_length[50]',
            'phone'       => 'max_length[20]',
            'email'       => 'valid_email|max_length[150]',
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
            'user_id'     => $this->request->getPost('user_id'),
            'employee_id' => $this->request->getPost('employee_id'),
            'name'        => $this->request->getPost('name'),
            'sip'         => $this->request->getPost('sip'),
            'phone'       => $this->request->getPost('phone'),
            'email'       => $this->request->getPost('email'),
        ];

        $result = $this->nurseService->update($uuid, $data);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupdate data perawat',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data perawat');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data perawat berhasil diupdate',
            ]);
        }

        return redirect()->to('/nurses/' . $uuid)->with('success', 'Data perawat berhasil diupdate');
    }

    public function delete(string $uuid)
    {
        $result = $this->nurseService->delete($uuid);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus data perawat',
                ])->setStatusCode(500);
            }

            return redirect()->to('/nurses')->with('error', 'Gagal menghapus data perawat');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data perawat berhasil dihapus',
            ]);
        }

        return redirect()->to('/nurses')->with('success', 'Data perawat berhasil dihapus');
    }

    private function isApiRequest(): bool
    {
        $accept = $this->request->getHeaderLine('Accept');
        $contentType = $this->request->getHeaderLine('Content-Type');

        return str_contains($accept, 'application/json') || str_contains($contentType, 'application/json');
    }
}
