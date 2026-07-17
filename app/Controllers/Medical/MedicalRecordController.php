<?php

namespace App\Controllers\Medical;

use App\Controllers\BaseController;
use App\Services\Medical\MedicalRecordService;
use App\Models\VisitModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\ICD10Model;
use App\Models\ActionTypeModel;

class MedicalRecordController extends BaseController
{
    private MedicalRecordService $recordService;
    private VisitModel $visitModel;
    private PatientModel $patientModel;
    private DoctorModel $doctorModel;
    private ICD10Model $icd10Model;
    private ActionTypeModel $actionTypeModel;

    public function __construct()
    {
        $this->recordService = new MedicalRecordService();
        $this->visitModel = new VisitModel();
        $this->patientModel = new PatientModel();
        $this->doctorModel = new DoctorModel();
        $this->icd10Model = new ICD10Model();
        $this->actionTypeModel = new ActionTypeModel();
    }

    public function index()
    {
        $visitId = $this->request->getGet('visit_id');
        $records = [];

        if ($visitId) {
            $records = $this->recordService->getByVisitId((int) $visitId);
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'data'    => $records,
            ]);
        }

        return view('pages/medical/records/index', [
            'title'   => 'Daftar Rekam Medis',
            'records' => $records,
            'visitId' => $visitId,
        ]);
    }

    public function create()
    {
        $visitId = $this->request->getGet('visit_id');

        $visit = $this->visitModel
            ->select('visits.*, patients.name as patient_name, patients.mrn, patients.id as patient_id')
            ->join('patients', 'patients.id = visits.patient_id', 'left')
            ->where('visits.id', $visitId)
            ->first();

        if ($visit === null) {
            return redirect()->back()->with('error', 'Kunjungan tidak ditemukan');
        }

        $doctors = $this->doctorModel->findAll();
        $templates = $this->recordService->getTemplates();

        return view('pages/medical/records/create', [
            'title'     => 'Buat Rekam Medis',
            'visit'     => $visit,
            'doctors'   => $doctors,
            'templates' => $templates,
        ]);
    }

    public function store()
    {
        $rules = [
            'visit_id'   => 'required|integer',
            'patient_id' => 'required|integer',
            'doctor_id'  => 'required|integer',
            'subjective' => 'permit_empty',
            'objective'  => 'permit_empty',
            'assessment' => 'permit_empty',
            'plan'       => 'permit_empty',
            'notes'      => 'permit_empty|max_length[5000]',
        ];

        if (!$this->validate($rules)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'visit_id'   => $this->request->getPost('visit_id'),
            'patient_id' => $this->request->getPost('patient_id'),
            'doctor_id'  => $this->request->getPost('doctor_id'),
            'subjective' => $this->request->getPost('subjective'),
            'objective'  => $this->request->getPost('objective'),
            'assessment' => $this->request->getPost('assessment'),
            'plan'       => $this->request->getPost('plan'),
            'notes'      => $this->request->getPost('notes'),
        ];

        $record = $this->recordService->create($data);

        if ($record === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan rekam medis',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan rekam medis');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rekam medis berhasil disimpan',
                'data'    => $record,
            ])->setStatusCode(201);
        }

        return redirect()->to('/medical/records/' . $record->uuid)->with('success', 'Rekam medis berhasil disimpan');
    }

    public function show(string $uuid)
    {
        $record = $this->recordService->getByUuid($uuid);

        if ($record === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Rekam medis tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->back()->with('error', 'Rekam medis tidak ditemukan');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'data'    => $record,
            ]);
        }

        return view('pages/medical/records/show', [
            'title'  => 'Detail Rekam Medis',
            'record' => $record,
        ]);
    }

    public function edit(string $uuid)
    {
        $record = $this->recordService->getByUuid($uuid);

        if ($record === null) {
            return redirect()->back()->with('error', 'Rekam medis tidak ditemukan');
        }

        $doctors = $this->doctorModel->findAll();
        $templates = $this->recordService->getTemplates();

        return view('pages/medical/records/edit', [
            'title'     => 'Edit Rekam Medis',
            'record'    => $record,
            'doctors'   => $doctors,
            'templates' => $templates,
        ]);
    }

    public function update(string $uuid)
    {
        $rules = [
            'doctor_id'  => 'required|integer',
            'subjective' => 'permit_empty',
            'objective'  => 'permit_empty',
            'assessment' => 'permit_empty',
            'plan'       => 'permit_empty',
            'notes'      => 'permit_empty|max_length[5000]',
        ];

        if (!$this->validate($rules)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'doctor_id'  => $this->request->getPost('doctor_id'),
            'subjective' => $this->request->getPost('subjective'),
            'objective'  => $this->request->getPost('objective'),
            'assessment' => $this->request->getPost('assessment'),
            'plan'       => $this->request->getPost('plan'),
            'notes'      => $this->request->getPost('notes'),
        ];

        $result = $this->recordService->update($uuid, $data);

        if (!$result) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengupdate rekam medis',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate rekam medis');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Rekam medis berhasil diupdate',
            ]);
        }

        return redirect()->to('/medical/records/' . $uuid)->with('success', 'Rekam medis berhasil diupdate');
    }

    public function addDiagnosis(string $uuid)
    {
        $rules = [
            'icd10_code_id'  => 'required|integer',
            'diagnosis_type' => 'required|in_list[primary,secondary]',
            'notes'          => 'permit_empty|max_length[5000]',
        ];

        if (!$this->validate($rules)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $diagnosis = $this->recordService->addDiagnosis(
            $uuid,
            (int) $this->request->getPost('icd10_code_id'),
            $this->request->getPost('diagnosis_type'),
            $this->request->getPost('notes')
        );

        if ($diagnosis === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menambahkan diagnosis',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan diagnosis');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Diagnosis berhasil ditambahkan',
                'data'    => $diagnosis,
            ])->setStatusCode(201);
        }

        return redirect()->to('/medical/records/' . $uuid)->with('success', 'Diagnosis berhasil ditambahkan');
    }

    public function addAction(string $uuid)
    {
        $rules = [
            'action_type_id' => 'required|integer',
            'quantity'       => 'required|integer|greater_than[0]',
            'unit_price'     => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $action = $this->recordService->addAction(
            $uuid,
            (int) $this->request->getPost('action_type_id'),
            (int) $this->request->getPost('quantity'),
            (float) $this->request->getPost('unit_price')
        );

        if ($action === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menambahkan tindakan',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan tindakan');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tindakan berhasil ditambahkan',
                'data'    => $action,
            ])->setStatusCode(201);
        }

        return redirect()->to('/medical/records/' . $uuid)->with('success', 'Tindakan berhasil ditambahkan');
    }

    public function searchICD10()
    {
        $query = $this->request->getGet('q');

        if (empty($query)) {
            return $this->response->setJSON([
                'success' => true,
                'data'    => [],
            ]);
        }

        $results = $this->recordService->searchICD10($query);

        return $this->response->setJSON([
            'success' => true,
            'data'    => $results,
        ]);
    }

    public function getActionTypes()
    {
        $query = $this->request->getGet('q');

        $builder = $this->actionTypeModel->where('is_active', true);

        if (!empty($query)) {
            $builder = $builder
                ->groupStart()
                    ->like('name', $query)
                    ->orLike('code', $query)
                ->groupEnd();
        }

        $actionTypes = $builder->orderBy('name', 'ASC')->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $actionTypes,
        ]);
    }

    public function getTemplates()
    {
        $category = $this->request->getGet('category');
        $templates = $this->recordService->getTemplates($category);

        return $this->response->setJSON([
            'success' => true,
            'data'    => $templates,
        ]);
    }

    public function addVitalSign(int $visitId)
    {
        $rules = [
            'patient_id'       => 'required|integer',
            'blood_pressure'   => 'permit_empty|max_length[20]',
            'heart_rate'       => 'permit_empty|integer|greater_than[0]',
            'temperature'      => 'permit_empty|decimal',
            'respiratory_rate' => 'permit_empty|integer|greater_than[0]',
            'spo2'             => 'permit_empty|decimal|greater_than[0]|less_than_equal_to[100]',
            'weight'           => 'permit_empty|decimal|greater_than[0]',
            'height'           => 'permit_empty|decimal|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'patient_id'       => $this->request->getPost('patient_id'),
            'blood_pressure'   => $this->request->getPost('blood_pressure'),
            'heart_rate'       => $this->request->getPost('heart_rate') ? (int) $this->request->getPost('heart_rate') : null,
            'temperature'      => $this->request->getPost('temperature') ? (float) $this->request->getPost('temperature') : null,
            'respiratory_rate' => $this->request->getPost('respiratory_rate') ? (int) $this->request->getPost('respiratory_rate') : null,
            'spo2'             => $this->request->getPost('spo2') ? (float) $this->request->getPost('spo2') : null,
            'weight'           => $this->request->getPost('weight') ? (float) $this->request->getPost('weight') : null,
            'height'           => $this->request->getPost('height') ? (float) $this->request->getPost('height') : null,
            'recorded_by'      => user_id(),
            'recorded_at'      => date('Y-m-d H:i:s'),
        ];

        $vitalSign = $this->recordService->addVitalSign($visitId, $data);

        if ($vitalSign === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan tanda vital',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan tanda vital');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tanda vital berhasil disimpan',
                'data'    => $vitalSign,
            ])->setStatusCode(201);
        }

        return redirect()->back()->with('success', 'Tanda vital berhasil disimpan');
    }

    private function isApiRequest(): bool
    {
        $accept = $this->request->getHeaderLine('Accept');
        $contentType = $this->request->getHeaderLine('Content-Type');

        return str_contains($accept, 'application/json') || str_contains($contentType, 'application/json');
    }
}
