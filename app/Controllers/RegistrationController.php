<?php

namespace App\Controllers;

use App\Services\RegistrationService;
use App\Models\DoctorModel;
use App\Models\PolyclinicModel;
use App\Models\PatientModel;
use App\Models\RoomModel;
use App\Models\BedModel;
use App\Models\VisitTypeModel;

class RegistrationController extends BaseController
{
    private RegistrationService $registrationService;
    private DoctorModel $doctorModel;
    private PolyclinicModel $polyclinicModel;
    private PatientModel $patientModel;
    private RoomModel $roomModel;
    private BedModel $bedModel;
    private VisitTypeModel $visitTypeModel;

    public function __construct()
    {
        $this->registrationService = new RegistrationService();
        $this->doctorModel = new DoctorModel();
        $this->polyclinicModel = new PolyclinicModel();
        $this->patientModel = new PatientModel();
        $this->roomModel = new RoomModel();
        $this->bedModel = new BedModel();
        $this->visitTypeModel = new VisitTypeModel();
    }

    public function index(): string
    {
        $status = $this->request->getGet('status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $page = (int) $this->request->getGet('page', 1);

        $result = $this->registrationService->getVisits($status, $dateFrom, $dateTo, $page);

        $data = [
            'title'      => 'Kunjungan',
            'visits'     => $result['visits'],
            'total'      => $result['total'],
            'page'       => $result['page'],
            'perPage'    => $result['perPage'],
            'totalPages' => $result['totalPages'],
            'status'     => $status,
            'dateFrom'   => $dateFrom,
            'dateTo'     => $dateTo,
        ];

        return view('pages/registration/index', $data);
    }

    public function create(): string
    {
        $patientId = $this->request->getGet('patient_id');

        $data = [
            'title'       => 'Pendaftaran Kunjungan',
            'doctors'     => $this->doctorModel->where('is_active', true)->findAll(),
            'polyclinics' => $this->polyclinicModel->where('is_active', true)->findAll(),
            'patients'    => $this->patientModel->where('status', 'active')->findAll(),
            'rooms'       => $this->roomModel->where('is_active', true)->findAll(),
            'visitTypes'  => $this->visitTypeModel->findAll(),
            'patientId'   => $patientId,
        ];

        return view('pages/registration/create', $data);
    }

    public function store()
    {
        $visitType = $this->request->getPost('visit_type');
        $patientId = (int) $this->request->getPost('patient_id');
        $doctorId = (int) $this->request->getPost('doctor_id');
        $polyclinicId = (int) $this->request->getPost('polyclinic_id');
        $complaint = $this->request->getPost('complaint');

        $rules = [
            'patient_id'    => 'required|integer',
            'visit_type'    => 'required|in_list[RJ,RI,IGD]',
        ];

        if ($visitType === 'RJ') {
            $rules['doctor_id'] = 'required|integer';
            $rules['polyclinic_id'] = 'required|integer';
        } elseif ($visitType === 'RI') {
            $rules['doctor_id'] = 'required|integer';
            $rules['polyclinic_id'] = 'required|integer';
            $rules['room_id'] = 'required|integer';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $visit = null;

        if ($visitType === 'RJ') {
            $visit = $this->registrationService->registerOutpatient($patientId, $doctorId, $polyclinicId, $complaint);
        } elseif ($visitType === 'RI') {
            $roomId = (int) $this->request->getPost('room_id');
            $bedId = $this->request->getPost('bed_id') ? (int) $this->request->getPost('bed_id') : null;
            $visit = $this->registrationService->registerInpatient($patientId, $doctorId, $polyclinicId, $roomId, $bedId, $complaint);
        } elseif ($visitType === 'IGD') {
            $doctorId = $this->request->getPost('doctor_id') ? (int) $this->request->getPost('doctor_id') : null;
            $visit = $this->registrationService->registerEmergency($patientId, $doctorId, $complaint);
        }

        if ($visit === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan kunjungan.');
        }

        return redirect()->to('/registration/' . $visit->uuid)->with('success', 'Kunjungan ' . $visit->visit_number . ' berhasil didaftarkan.');
    }

    public function show(string $uuid): string
    {
        $visit = $this->registrationService->getVisitByUuid($uuid);

        if ($visit === null) {
            return redirect()->to('/registration')->with('error', 'Kunjungan tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Kunjungan',
            'visit' => $visit,
        ];

        return view('pages/registration/show', $data);
    }

    public function updateStatus(string $uuid)
    {
        $status = $this->request->getPost('status');

        $rules = [
            'status' => 'required|in_list[waiting,in_progress,completed,cancelled,no_show]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        if (!$this->registrationService->updateStatus($uuid, $status)) {
            return redirect()->back()->with('error', 'Gagal mengubah status kunjungan.');
        }

        return redirect()->back()->with('success', 'Status kunjungan berhasil diubah.');
    }

    public function discharge(string $uuid)
    {
        if (!$this->registrationService->discharge($uuid)) {
            return redirect()->back()->with('error', 'Gagal melakukan discharge.');
        }

        return redirect()->back()->with('success', 'Pasien berhasil di-discharge.');
    }

    public function getBeds()
    {
        $roomId = $this->request->getGet('room_id');
        if (!$roomId) {
            return $this->response->setJSON([]);
        }

        $beds = $this->bedModel
            ->where('room_id', $roomId)
            ->where('status', 'available')
            ->findAll();

        return $this->response->setJSON($beds);
    }
}
