<?php

namespace App\Controllers;

use App\Services\QueueService;
use App\Models\DoctorModel;
use App\Models\PolyclinicModel;
use App\Models\PatientModel;

class QueueController extends BaseController
{
    private QueueService $queueService;
    private DoctorModel $doctorModel;
    private PolyclinicModel $polyclinicModel;
    private PatientModel $patientModel;

    public function __construct()
    {
        $this->queueService = new QueueService();
        $this->doctorModel = new DoctorModel();
        $this->polyclinicModel = new PolyclinicModel();
        $this->patientModel = new PatientModel();
    }

    public function index(): string
    {
        $polyclinicId = $this->request->getGet('polyclinic_id') ? (int) $this->request->getGet('polyclinic_id') : null;
        $status = $this->request->getGet('status');

        $data = [
            'title'        => 'Antrian',
            'queues'       => $this->queueService->getTodayQueues($polyclinicId, $status),
            'polyclinics'  => $this->polyclinicModel->where('is_active', true)->findAll(),
            'polyclinicId' => $polyclinicId,
            'status'       => $status,
        ];

        return view('pages/queues/index', $data);
    }

    public function create(): string
    {
        $data = [
            'title'       => 'Tambah Antrian',
            'doctors'     => $this->doctorModel->where('is_active', true)->findAll(),
            'polyclinics' => $this->polyclinicModel->where('is_active', true)->findAll(),
            'patients'    => $this->patientModel->where('is_active', true)->findAll(),
        ];

        return view('pages/queues/create', $data);
    }

    public function store()
    {
        $rules = [
            'patient_id'    => 'required|integer',
            'doctor_id'     => 'required|integer',
            'polyclinic_id' => 'required|integer',
            'visit_date'    => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $queue = $this->queueService->generateQueue(
            $this->request->getPost('patient_id'),
            $this->request->getPost('doctor_id'),
            $this->request->getPost('polyclinic_id'),
            $this->request->getPost('visit_date')
        );

        if ($queue === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat nomor antrian.');
        }

        return redirect()->to('/queues')->with('success', 'Nomor antrian ' . $queue->queue_number . ' berhasil dibuat.');
    }

    public function call(string $uuid)
    {
        if (!$this->queueService->callQueue($uuid)) {
            return redirect()->back()->with('error', 'Gagal memanggil antrian.');
        }

        return redirect()->back()->with('success', 'Antrian berhasil dipanggil.');
    }

    public function skip(string $uuid)
    {
        if (!$this->queueService->skipQueue($uuid)) {
            return redirect()->back()->with('error', 'Gagal melewati antrian.');
        }

        return redirect()->back()->with('success', 'Antrian berhasil dilewati.');
    }

    public function recall(string $uuid)
    {
        if (!$this->queueService->recallQueue($uuid)) {
            return redirect()->back()->with('error', 'Gagal memanggil ulang antrian.');
        }

        return redirect()->back()->with('success', 'Antrian berhasil dipanggil ulang.');
    }

    public function complete(string $uuid)
    {
        if (!$this->queueService->completeQueue($uuid)) {
            return redirect()->back()->with('error', 'Gagal menyelesaikan antrian.');
        }

        return redirect()->back()->with('success', 'Antrian berhasil diselesaikan.');
    }

    public function display(): string
    {
        $polyclinicId = $this->request->getGet('polyclinic_id') ? (int) $this->request->getGet('polyclinic_id') : null;

        $data = [
            'title'        => 'Display Antrian',
            'displayData'  => $this->queueService->getDisplayData($polyclinicId),
            'polyclinics'  => $this->polyclinicModel->where('is_active', true)->findAll(),
            'polyclinicId' => $polyclinicId,
        ];

        return view('pages/queues/display', $data);
    }
}
