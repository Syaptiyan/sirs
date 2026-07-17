<?php

namespace App\Controllers\Patient;

use App\Controllers\BaseController;
use App\Services\Patient\PatientService;

class PatientController extends BaseController
{
    private PatientService $patientService;

    public function __construct()
    {
        $this->patientService = new PatientService();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $gender = $this->request->getGet('gender');
        $isActive = $this->request->getGet('is_active');
        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('per_page') ?: 20;

        $activeFilter = null;
        if ($isActive !== null && $isActive !== '') {
            $activeFilter = $isActive === '1';
        }

        $result = $this->patientService->getAll($search, $gender, $activeFilter, $page, $perPage);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data pasien berhasil diambil',
                'data' => $result['data'],
                'meta' => $result['meta'],
            ]);
        }

        return view('patients/index', [
            'patients' => $result['data'],
            'meta' => $result['meta'],
            'filters' => [
                'search' => $search,
                'gender' => $gender,
                'is_active' => $isActive,
            ],
        ]);
    }

    public function create()
    {
        return view('patients/create');
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'gender' => 'required|in_list[M,F]',
            'birth_date' => 'required|valid_date',
            'phone' => 'max_length[20]',
            'email' => 'valid_email|max_length[255]',
            'nik' => 'exact_length[16]|numeric',
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
            'name' => $this->request->getPost('name'),
            'gender' => $this->request->getPost('gender'),
            'birth_place' => $this->request->getPost('birth_place'),
            'birth_date' => $this->request->getPost('birth_date'),
            'nik' => $this->request->getPost('nik'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'address' => $this->request->getPost('address'),
            'blood_type' => $this->request->getPost('blood_type'),
            'religion' => $this->request->getPost('religion'),
            'occupation' => $this->request->getPost('occupation'),
            'marital_status' => $this->request->getPost('marital_status'),
            'emergency_contact_name' => $this->request->getPost('emergency_contact_name'),
            'emergency_contact_phone' => $this->request->getPost('emergency_contact_phone'),
            'notes' => $this->request->getPost('notes'),
            'is_active' => 1,
        ];

        $patient = $this->patientService->create($data);

        if ($patient === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data pasien',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data pasien');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data pasien berhasil disimpan',
                'data' => $patient,
            ])->setStatusCode(201);
        }

        return redirect()->to('/patients')->with('success', 'Data pasien berhasil disimpan');
    }

    public function show(string $uuid)
    {
        $patient = $this->patientService->getByUuid($uuid);

        if ($patient === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Pasien tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->to('/patients')->with('error', 'Pasien tidak ditemukan');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data pasien berhasil diambil',
                'data' => $patient,
            ]);
        }

        return view('patients/show', ['patient' => $patient]);
    }

    public function edit(string $uuid)
    {
        $patient = $this->patientService->getByUuid($uuid);

        if ($patient === null) {
            return redirect()->to('/patients')->with('error', 'Pasien tidak ditemukan');
        }

        return view('patients/edit', ['patient' => $patient]);
    }

    public function update(string $uuid)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'gender' => 'required|in_list[M,F]',
            'birth_date' => 'required|valid_date',
            'phone' => 'max_length[20]',
            'email' => 'valid_email|max_length[255]',
            'nik' => 'exact_length[16]|numeric',
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
            'name' => $this->request->getPost('name'),
            'gender' => $this->request->getPost('gender'),
            'birth_place' => $this->request->getPost('birth_place'),
            'birth_date' => $this->request->getPost('birth_date'),
            'nik' => $this->request->getPost('nik'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'address' => $this->request->getPost('address'),
            'blood_type' => $this->request->getPost('blood_type'),
            'religion' => $this->request->getPost('religion'),
            'occupation' => $this->request->getPost('occupation'),
            'marital_status' => $this->request->getPost('marital_status'),
            'emergency_contact_name' => $this->request->getPost('emergency_contact_name'),
            'emergency_contact_phone' => $this->request->getPost('emergency_contact_phone'),
            'notes' => $this->request->getPost('notes'),
        ];

        $result = $this->patientService->update($uuid, $data);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupdate data pasien',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data pasien');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data pasien berhasil diupdate',
            ]);
        }

        return redirect()->to('/patients/' . $uuid)->with('success', 'Data pasien berhasil diupdate');
    }

    public function delete(string $uuid)
    {
        $result = $this->patientService->delete($uuid);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus data pasien',
                ])->setStatusCode(500);
            }

            return redirect()->to('/patients')->with('error', 'Gagal menghapus data pasien');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data pasien berhasil dihapus',
            ]);
        }

        return redirect()->to('/patients')->with('success', 'Data pasien berhasil dihapus');
    }

    public function visits(string $uuid)
    {
        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('per_page') ?: 20;

        $result = $this->patientService->getVisits($uuid, $page, $perPage);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Riwayat kunjungan berhasil diambil',
                'data' => $result['data'],
                'meta' => $result['meta'],
            ]);
        }

        return view('patients/visits', [
            'visits' => $result['data'],
            'meta' => $result['meta'],
            'patientUuid' => $uuid,
        ]);
    }

    public function allergies(string $uuid)
    {
        $allergies = $this->patientService->getAllergies($uuid);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data alergi berhasil diambil',
                'data' => $allergies,
            ]);
        }

        return view('patients/allergies', [
            'allergies' => $allergies,
            'patientUuid' => $uuid,
        ]);
    }

    public function storeAllergy(string $uuid)
    {
        $rules = [
            'allergy_name' => 'required|max_length[255]',
            'severity' => 'required|in_list[mild,moderate,severe]',
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
            'allergy_name' => $this->request->getPost('allergy_name'),
            'severity' => $this->request->getPost('severity'),
            'reaction' => $this->request->getPost('reaction'),
            'notes' => $this->request->getPost('notes'),
        ];

        $allergy = $this->patientService->addAllergy($uuid, $data);

        if ($allergy === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menambahkan alergi',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan alergi');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Alergi berhasil ditambahkan',
                'data' => $allergy,
            ])->setStatusCode(201);
        }

        return redirect()->to('/patients/' . $uuid . '/allergies')->with('success', 'Alergi berhasil ditambahkan');
    }

    public function deleteAllergy(string $allergyUuid)
    {
        $result = $this->patientService->removeAllergy($allergyUuid);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus alergi',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Gagal menghapus alergi');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Alergi berhasil dihapus',
            ]);
        }

        return redirect()->back()->with('success', 'Alergi berhasil dihapus');
    }

    public function chronicDiseases(string $uuid)
    {
        $diseases = $this->patientService->getChronicDiseases($uuid);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data penyakit kronis berhasil diambil',
                'data' => $diseases,
            ]);
        }

        return view('patients/chronic-diseases', [
            'diseases' => $diseases,
            'patientUuid' => $uuid,
        ]);
    }

    public function storeChronicDisease(string $uuid)
    {
        $rules = [
            'disease_name' => 'required|max_length[255]',
            'diagnosed_date' => 'valid_date',
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
            'disease_name' => $this->request->getPost('disease_name'),
            'diagnosed_date' => $this->request->getPost('diagnosed_date'),
            'treatment' => $this->request->getPost('treatment'),
            'notes' => $this->request->getPost('notes'),
        ];

        $disease = $this->patientService->addChronicDisease($uuid, $data);

        if ($disease === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menambahkan penyakit kronis',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan penyakit kronis');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Penyakit kronis berhasil ditambahkan',
                'data' => $disease,
            ])->setStatusCode(201);
        }

        return redirect()->to('/patients/' . $uuid . '/chronic-diseases')->with('success', 'Penyakit kronis berhasil ditambahkan');
    }

    public function documents(string $uuid)
    {
        $documents = $this->patientService->getDocuments($uuid);

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data dokumen berhasil diambil',
                'data' => $documents,
            ]);
        }

        return view('patients/documents', [
            'documents' => $documents,
            'patientUuid' => $uuid,
        ]);
    }

    public function storeDocument(string $uuid)
    {
        $file = $this->request->getFile('document');

        if ($file === null || !$file->isValid()) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'File dokumen tidak valid',
                ])->setStatusCode(422);
            }

            return redirect()->back()->with('error', 'File dokumen tidak valid');
        }

        $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
        $ext = $file->getExtension();

        if (!in_array(strtolower($ext), $allowedTypes)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tipe file tidak diizinkan. Format yang diizinkan: ' . implode(', ', $allowedTypes),
                ])->setStatusCode(422);
            }

            return redirect()->back()->with('error', 'Tipe file tidak diizinkan');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/patients/' . $uuid, $newName);

        $data = [
            'document_name' => $this->request->getPost('document_name') ?? $file->getClientName(),
            'document_type' => $this->request->getPost('document_type'),
            'file_path' => 'uploads/patients/' . $uuid . '/' . $newName,
            'file_name' => $file->getClientName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ];

        $document = $this->patientService->addDocument($uuid, $data);

        if ($document === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan dokumen',
                ])->setStatusCode(500);
            }

            return redirect()->back()->with('error', 'Gagal menyimpan dokumen');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Dokumen berhasil diupload',
                'data' => $document,
            ])->setStatusCode(201);
        }

        return redirect()->to('/patients/' . $uuid . '/documents')->with('success', 'Dokumen berhasil diupload');
    }

    public function export()
    {
        $format = $this->request->getGet('format') ?? 'csv';
        $csv = $this->patientService->export($format);

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="pasien_' . date('YmdHis') . '.csv"')
            ->setBody($csv);
    }

    private function isApiRequest(): bool
    {
        $accept = $this->request->getHeaderLine('Accept');
        $contentType = $this->request->getHeaderLine('Content-Type');

        return str_contains($accept, 'application/json') || str_contains($contentType, 'application/json');
    }
}
