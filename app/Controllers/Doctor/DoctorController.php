<?php

namespace App\Controllers\Doctor;

use App\Controllers\BaseController;
use App\Services\Doctor\DoctorService;

class DoctorController extends BaseController
{
    private DoctorService $doctorService;

    public function __construct()
    {
        $this->doctorService = new DoctorService();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $specializationId = $this->request->getGet('specialization_id');
        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('per_page') ?: 20;

        $specId = null;
        if ($specializationId !== null && $specializationId !== '') {
            $specId = (int) $specializationId;
        }

        $result = $this->doctorService->getAll($search, $specId, $page, $perPage);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data dokter berhasil diambil',
                'data' => $result['data'],
                'meta' => $result['meta'],
            ]);
        }

        return view('doctors/index', [
            'doctors' => $result['data'],
            'meta' => $result['meta'],
            'filters' => [
                'search' => $search,
                'specialization_id' => $specializationId,
            ],
        ]);
    }

    public function create()
    {
        $specializations = $this->doctorService->getSpecializations();

        return view('doctors/create', [
            'specializations' => $specializations,
        ]);
    }

    public function store()
    {
        $rules = [
            'employee_id'       => 'required|max_length[20]|is_unique[doctors.employee_id]',
            'name'              => 'required|min_length[3]|max_length[150]',
            'sip'               => 'required|max_length[50]|is_unique[doctors.sip]',
            'specialization_id' => 'required|integer',
            'phone'             => 'max_length[20]',
            'email'             => 'valid_email|max_length[150]',
            'consultation_fee'  => 'decimal',
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
            'user_id'           => $this->request->getPost('user_id'),
            'employee_id'       => $this->request->getPost('employee_id'),
            'name'              => $this->request->getPost('name'),
            'sip'               => $this->request->getPost('sip'),
            'specialization_id' => $this->request->getPost('specialization_id'),
            'phone'             => $this->request->getPost('phone'),
            'email'             => $this->request->getPost('email'),
            'bio'               => $this->request->getPost('bio'),
            'consultation_fee'  => $this->request->getPost('consultation_fee') ?: 0,
            'is_active'         => 1,
        ];

        $doctor = $this->doctorService->create($data);

        if ($doctor === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data dokter',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data dokter');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data dokter berhasil disimpan',
                'data' => $doctor,
            ])->setStatusCode(201);
        }

        return redirect()->to('/doctors')->with('success', 'Data dokter berhasil disimpan');
    }

    public function show(string $uuid)
    {
        $doctor = $this->doctorService->getByUuid($uuid);

        if ($doctor === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Dokter tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->to('/doctors')->with('error', 'Dokter tidak ditemukan');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data dokter berhasil diambil',
                'data' => $doctor,
            ]);
        }

        return view('doctors/show', ['doctor' => $doctor]);
    }

    public function edit(string $uuid)
    {
        $doctor = $this->doctorService->getByUuid($uuid);

        if ($doctor === null) {
            return redirect()->to('/doctors')->with('error', 'Dokter tidak ditemukan');
        }

        $specializations = $this->doctorService->getSpecializations();

        return view('doctors/edit', [
            'doctor' => $doctor,
            'specializations' => $specializations,
        ]);
    }

    public function update(string $uuid)
    {
        $rules = [
            'employee_id'       => 'required|max_length[20]',
            'name'              => 'required|min_length[3]|max_length[150]',
            'sip'               => 'required|max_length[50]',
            'specialization_id' => 'required|integer',
            'phone'             => 'max_length[20]',
            'email'             => 'valid_email|max_length[150]',
            'consultation_fee'  => 'decimal',
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
            'user_id'           => $this->request->getPost('user_id'),
            'employee_id'       => $this->request->getPost('employee_id'),
            'name'              => $this->request->getPost('name'),
            'sip'               => $this->request->getPost('sip'),
            'specialization_id' => $this->request->getPost('specialization_id'),
            'phone'             => $this->request->getPost('phone'),
            'email'             => $this->request->getPost('email'),
            'bio'               => $this->request->getPost('bio'),
            'consultation_fee'  => $this->request->getPost('consultation_fee') ?: 0,
        ];

        $result = $this->doctorService->update($uuid, $data);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupdate data dokter',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data dokter');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data dokter berhasil diupdate',
            ]);
        }

        return redirect()->to('/doctors/' . $uuid)->with('success', 'Data dokter berhasil diupdate');
    }

    public function delete(string $uuid)
    {
        $result = $this->doctorService->delete($uuid);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus data dokter',
                ])->setStatusCode(500);
            }

            return redirect()->to('/doctors')->with('error', 'Gagal menghapus data dokter');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data dokter berhasil dihapus',
            ]);
        }

        return redirect()->to('/doctors')->with('success', 'Data dokter berhasil dihapus');
    }

    public function schedules(string $uuid)
    {
        $schedules = $this->doctorService->getSchedules($uuid);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Jadwal dokter berhasil diambil',
                'data' => $schedules,
            ]);
        }

        return view('doctors/schedules', [
            'schedules' => $schedules,
            'doctorUuid' => $uuid,
        ]);
    }

    private function isApiRequest(): bool
    {
        $accept = $this->request->getHeaderLine('Accept');
        $contentType = $this->request->getHeaderLine('Content-Type');

        return str_contains($accept, 'application/json') || str_contains($contentType, 'application/json');
    }
}
