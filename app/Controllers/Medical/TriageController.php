<?php

namespace App\Controllers\Medical;

use App\Controllers\BaseController;
use App\Services\Medical\TriageService;
use App\Models\VisitModel;
use App\Models\PatientModel;

class TriageController extends BaseController
{
    private TriageService $triageService;
    private VisitModel $visitModel;
    private PatientModel $patientModel;

    public function __construct()
    {
        $this->triageService = new TriageService();
        $this->visitModel = new VisitModel();
        $this->patientModel = new PatientModel();
    }

    public function create(int $visitId)
    {
        $visit = $this->visitModel
            ->select('visits.*, patients.name as patient_name, patients.mrn')
            ->join('patients', 'patients.id = visits.patient_id', 'left')
            ->where('visits.id', $visitId)
            ->first();

        if ($visit === null) {
            return redirect()->back()->with('error', 'Kunjungan tidak ditemukan');
        }

        $existingTriage = $this->triageService->getByVisitId($visitId);

        return view('pages/medical/triages/form', [
            'title'          => 'Form Triase IGD',
            'visit'          => $visit,
            'existingTriage' => $existingTriage,
        ]);
    }

    public function store()
    {
        $vitalSignsRules = [
            'vital_signs.blood_pressure'    => 'required|max_length[20]',
            'vital_signs.heart_rate'        => 'required|integer|greater_than[0]',
            'vital_signs.temperature'       => 'required|decimal',
            'vital_signs.respiratory_rate'  => 'required|integer|greater_than[0]',
            'vital_signs.spo2'              => 'required|decimal|greater_than[0]|less_than_equal_to[100]',
        ];

        $rules = array_merge($vitalSignsRules, [
            'visit_id'            => 'required|integer',
            'patient_id'          => 'required|integer',
            'triage_level'        => 'required|in_list[emergency,urgent,non_urgent]',
            'chief_complaint'     => 'required',
            'consciousness_level' => 'required|in_list[alert,confused,drowsy,unresponsive]',
            'pain_scale'          => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[10]',
            'notes'               => 'permit_empty|max_length[5000]',
        ]);

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
            'visit_id'            => $this->request->getPost('visit_id'),
            'patient_id'          => $this->request->getPost('patient_id'),
            'triage_level'        => $this->request->getPost('triage_level'),
            'chief_complaint'     => $this->request->getPost('chief_complaint'),
            'vital_signs'         => [
                'blood_pressure'   => $this->request->getPost('vital_signs.blood_pressure'),
                'heart_rate'       => (int) $this->request->getPost('vital_signs.heart_rate'),
                'temperature'      => (float) $this->request->getPost('vital_signs.temperature'),
                'respiratory_rate' => (int) $this->request->getPost('vital_signs.respiratory_rate'),
                'spo2'             => (float) $this->request->getPost('vital_signs.spo2'),
            ],
            'consciousness_level' => $this->request->getPost('consciousness_level'),
            'pain_scale'          => (int) $this->request->getPost('pain_scale'),
            'notes'               => $this->request->getPost('notes'),
            'triaged_by'          => user_id(),
            'triaged_at'          => date('Y-m-d H:i:s'),
        ];

        $triage = $this->triageService->create($data);

        if ($triage === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan data triase',
                ])->setStatusCode(500);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data triase');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data triase berhasil disimpan',
                'data'    => $triage,
            ])->setStatusCode(201);
        }

        return redirect()->to('/medical/triages/' . $triage->id)->with('success', 'Data triase berhasil disimpan');
    }

    public function show(int $id)
    {
        $triage = $this->triageService->getById($id);

        if ($triage === null) {
            if ($this->isApiRequest()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data triase tidak ditemukan',
                ])->setStatusCode(404);
            }

            return redirect()->back()->with('error', 'Data triase tidak ditemukan');
        }

        if ($this->isApiRequest()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data triase berhasil diambil',
                'data'    => $triage,
            ]);
        }

        return view('pages/medical/triages/show', [
            'title'  => 'Detail Triase',
            'triage' => $triage,
        ]);
    }

    private function isApiRequest(): bool
    {
        $accept = $this->request->getHeaderLine('Accept');
        $contentType = $this->request->getHeaderLine('Content-Type');

        return str_contains($accept, 'application/json') || str_contains($contentType, 'application/json');
    }
}
