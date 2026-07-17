<?php

namespace App\Controllers;

use App\Services\PrescriptionService;
use App\Models\PrescriptionModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\VisitModel;

class PrescriptionController extends BaseController
{
    private PrescriptionService $prescriptionService;
    private PrescriptionModel $prescriptionModel;
    private PatientModel $patientModel;
    private DoctorModel $doctorModel;
    private VisitModel $visitModel;

    public function __construct()
    {
        $this->prescriptionService = new PrescriptionService();
        $this->prescriptionModel = new PrescriptionModel();
        $this->patientModel = new PatientModel();
        $this->doctorModel = new DoctorModel();
        $this->visitModel = new VisitModel();
    }

    public function index(): string
    {
        $status = $this->request->getGet('status');

        $builder = $this->prescriptionModel
            ->select('prescriptions.*')
            ->select('patients.name as patient_name, patients.mrn')
            ->select('doctors.name as doctor_name')
            ->join('patients', 'patients.id = prescriptions.patient_id', 'left')
            ->join('doctors', 'doctors.id = prescriptions.doctor_id', 'left')
            ->orderBy('prescriptions.created_at', 'DESC');

        if ($status !== null && $status !== '') {
            $builder->where('prescriptions.status', $status);
        }

        $data = [
            'title'        => 'Resep',
            'prescriptions' => $builder->findAll(),
            'status'       => $status,
        ];

        return view('pages/prescriptions/index', $data);
    }

    public function create($visitId): string
    {
        $visit = $this->visitModel
            ->select('visits.*')
            ->select('patients.name as patient_name, patients.mrn')
            ->select('doctors.name as doctor_name')
            ->join('patients', 'patients.id = visits.patient_id', 'left')
            ->join('doctors', 'doctors.id = visits.doctor_id', 'left')
            ->where('visits.id', $visitId)
            ->first();

        $drugs = \Config\Database::connect()
            ->query('SELECT * FROM drugs WHERE is_active = 1 ORDER BY name ASC')
            ->getResult();

        $data = [
            'title'  => 'Buat Resep',
            'visit'  => $visit,
            'drugs'  => $drugs,
        ];

        return view('pages/prescriptions/create', $data);
    }

    public function store()
    {
        $rules = [
            'visit_id'              => 'required|integer',
            'patient_id'            => 'required|integer',
            'doctor_id'             => 'required|integer',
            'items'                 => 'required',
            'items.*.drug_id'       => 'required|integer',
            'items.*.quantity'      => 'required|decimal',
            'items.*.unit'          => 'required|max_length[20]',
            'items.*.dosage'        => 'required|max_length[100]',
            'items.*.frequency'     => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = [
            'notes'   => $this->request->getPost('notes'),
            'details' => $this->request->getPost('items'),
        ];

        $prescription = $this->prescriptionService->create(
            (int) $this->request->getPost('visit_id'),
            (int) $this->request->getPost('patient_id'),
            (int) $this->request->getPost('doctor_id'),
            $items
        );

        if ($prescription === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat resep.');
        }

        return redirect()->to('/prescriptions/' . $prescription->uuid)->with('success', 'Resep ' . $prescription->prescription_number . ' berhasil dibuat.');
    }

    public function show(string $uuid): string
    {
        $prescription = $this->prescriptionService->getByUuid($uuid);

        if ($prescription === null) {
            return redirect()->to('/prescriptions')->with('error', 'Resep tidak ditemukan.');
        }

        $data = [
            'title'       => 'Detail Resep',
            'prescription' => $prescription,
        ];

        return view('pages/prescriptions/show', $data);
    }

    public function dispense(string $uuid)
    {
        if (!$this->prescriptionService->dispense($uuid)) {
            return redirect()->back()->with('error', 'Gagal menyalakan resep. Pastikan status resep pending.');
        }

        return redirect()->back()->with('success', 'Resep berhasil didispensasi.');
    }

    public function cancel(string $uuid)
    {
        if (!$this->prescriptionService->cancel($uuid)) {
            return redirect()->back()->with('error', 'Gagal membatalkan resep.');
        }

        return redirect()->back()->with('success', 'Resep berhasil dibatalkan.');
    }

    public function print(string $uuid): string
    {
        $prescription = $this->prescriptionService->getByUuid($uuid);

        if ($prescription === null) {
            return redirect()->to('/prescriptions')->with('error', 'Resep tidak ditemukan.');
        }

        $data = [
            'title'       => 'Cetak Resep',
            'prescription' => $prescription,
        ];

        return view('pages/prescriptions/print', $data);
    }
}
