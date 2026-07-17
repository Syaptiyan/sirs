<?php

namespace App\Controllers\Lab;

use App\Controllers\BaseController;
use App\Services\Lab\LabService;
use App\Models\LabOrderModel;
use App\Models\LabTemplateModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\VisitModel;

class LabController extends BaseController
{
    private LabService $labService;
    private LabOrderModel $orderModel;
    private LabTemplateModel $templateModel;
    private PatientModel $patientModel;
    private DoctorModel $doctorModel;
    private VisitModel $visitModel;

    public function __construct()
    {
        $this->labService = new LabService();
        $this->orderModel = new LabOrderModel();
        $this->templateModel = new LabTemplateModel();
        $this->patientModel = new PatientModel();
        $this->doctorModel = new DoctorModel();
        $this->visitModel = new VisitModel();
    }

    public function index(): string
    {
        $status = $this->request->getGet('status');
        $page = (int) ($this->request->getGet('page') ?? 1);

        $data = [
            'title'  => 'Order Laboratorium',
            'result' => $this->labService->getOrders($status, $page),
            'status' => $status,
        ];

        return view('pages/lab/index', $data);
    }

    public function create($visitId = null): string
    {
        $visit = null;
        if ($visitId) {
            $visit = $this->visitModel
                ->select('visits.*')
                ->select('patients.name as patient_name, patients.mrn')
                ->select('doctors.name as doctor_name')
                ->join('patients', 'patients.id = visits.patient_id', 'left')
                ->join('doctors', 'doctors.id = visits.doctor_id', 'left')
                ->where('visits.id', $visitId)
                ->first();
        }

        $templates = $this->labService->getTemplates();
        $categories = array_unique(array_column($templates, 'category'));

        $data = [
            'title'      => 'Buat Order Lab',
            'visit'      => $visit,
            'templates'  => $templates,
            'categories' => $categories,
        ];

        return view('pages/lab/create', $data);
    }

    public function store()
    {
        $rules = [
            'visit_id'               => 'required|integer',
            'patient_id'             => 'required|integer',
            'doctor_id'              => 'required|integer',
            'items'                  => 'required',
            'items.*.parameter_name' => 'required|max_length[200]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = [
            'notes'   => $this->request->getPost('notes'),
            'details' => $this->request->getPost('items'),
        ];

        $order = $this->labService->createOrder(
            (int) $this->request->getPost('visit_id'),
            (int) $this->request->getPost('patient_id'),
            (int) $this->request->getPost('doctor_id'),
            $items
        );

        if ($order === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat order lab.');
        }

        return redirect()->to('/lab/' . $order->uuid)->with('success', 'Order lab ' . $order->order_number . ' berhasil dibuat.');
    }

    public function show(string $uuid): string
    {
        $order = $this->labService->getByUuid($uuid);

        if ($order === null) {
            return redirect()->to('/lab')->with('error', 'Order lab tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Order Lab',
            'order' => $order,
        ];

        return view('pages/lab/show', $data);
    }

    public function inputResults(string $uuid): string
    {
        $order = $this->labService->getByUuid($uuid);

        if ($order === null) {
            return redirect()->to('/lab')->with('error', 'Order lab tidak ditemukan.');
        }

        if ($order->status === 'completed') {
            return redirect()->to('/lab/' . $uuid)->with('error', 'Order lab sudah selesai.');
        }

        $data = [
            'title' => 'Input Hasil Lab',
            'order' => $order,
        ];

        return view('pages/lab/input_results', $data);
    }

    public function storeResults(string $uuid)
    {
        $order = $this->labService->getByUuid($uuid);

        if ($order === null) {
            return redirect()->to('/lab')->with('error', 'Order lab tidak ditemukan.');
        }

        $results = $this->request->getPost('items') ?? [];
        $notes = $this->request->getPost('notes');

        $resultData = [];
        foreach ($results as $item) {
            $resultData[] = [
                'id'           => $item['id'],
                'result_value' => $item['result_value'],
                'notes'        => $item['notes'] ?? null,
            ];
        }
        $resultData['notes'] = $notes;

        $userId = session()->get('user_id') ?? 1;

        if (!$this->labService->inputResults($uuid, $resultData, $userId)) {
            return redirect()->back()->with('error', 'Gagal menyimpan hasil lab.');
        }

        return redirect()->to('/lab/' . $uuid)->with('success', 'Hasil lab berhasil disimpan.');
    }

    public function print(string $uuid): string
    {
        $order = $this->labService->getByUuid($uuid);

        if ($order === null) {
            return redirect()->to('/lab')->with('error', 'Order lab tidak ditemukan.');
        }

        $data = [
            'title' => 'Cetak Hasil Lab',
            'order' => $order,
        ];

        return view('pages/lab/print', $data);
    }

    public function cancel(string $uuid)
    {
        if (!$this->labService->cancel($uuid)) {
            return redirect()->back()->with('error', 'Gagal membatalkan order lab.');
        }

        return redirect()->back()->with('success', 'Order lab berhasil dibatalkan.');
    }
}
