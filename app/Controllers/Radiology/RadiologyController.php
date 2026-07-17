<?php

namespace App\Controllers\Radiology;

use App\Controllers\BaseController;
use App\Services\Radiology\RadiologyService;
use App\Models\RadiologyTemplateModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\VisitModel;

class RadiologyController extends BaseController
{
    private RadiologyService $radiologyService;
    private RadiologyTemplateModel $templateModel;
    private PatientModel $patientModel;
    private DoctorModel $doctorModel;
    private VisitModel $visitModel;

    public function __construct()
    {
        $this->radiologyService = new RadiologyService();
        $this->templateModel = new RadiologyTemplateModel();
        $this->patientModel = new PatientModel();
        $this->doctorModel = new DoctorModel();
        $this->visitModel = new VisitModel();
    }

    public function index(): string
    {
        $status = $this->request->getGet('status');
        $page = (int) ($this->request->getGet('page') ?? 1);

        $data = [
            'title'  => 'Order Radiologi',
            'result' => $this->radiologyService->getOrders($status, $page),
            'status' => $status,
        ];

        return view('pages/radiology/index', $data);
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

        $templates = $this->radiologyService->getTemplates();
        $categories = array_unique(array_column($templates, 'category'));

        $data = [
            'title'      => 'Buat Order Radiologi',
            'visit'      => $visit,
            'templates'  => $templates,
            'categories' => $categories,
        ];

        return view('pages/radiology/create', $data);
    }

    public function store()
    {
        $rules = [
            'visit_id'    => 'required|integer',
            'patient_id'  => 'required|integer',
            'doctor_id'   => 'required|integer',
            'template_id' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $templateId = $this->request->getPost('template_id');
        $templateId = !empty($templateId) ? (int) $templateId : null;

        $order = $this->radiologyService->createOrder(
            (int) $this->request->getPost('visit_id'),
            (int) $this->request->getPost('patient_id'),
            (int) $this->request->getPost('doctor_id'),
            $templateId,
            $this->request->getPost('notes')
        );

        if ($order === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat order radiologi.');
        }

        return redirect()->to('/radiology/' . $order->uuid)->with('success', 'Order radiologi ' . $order->order_number . ' berhasil dibuat.');
    }

    public function show(string $uuid): string
    {
        $order = $this->radiologyService->getByUuid($uuid);

        if ($order === null) {
            return redirect()->to('/radiology')->with('error', 'Order radiologi tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Order Radiologi',
            'order' => $order,
        ];

        return view('pages/radiology/show', $data);
    }

    public function inputResult(string $uuid): string
    {
        $order = $this->radiologyService->getByUuid($uuid);

        if ($order === null) {
            return redirect()->to('/radiology')->with('error', 'Order radiologi tidak ditemukan.');
        }

        if ($order->status === 'completed') {
            return redirect()->to('/radiology/' . $uuid)->with('error', 'Order radiologi sudah selesai.');
        }

        $data = [
            'title' => 'Input Hasil Radiologi',
            'order' => $order,
        ];

        return view('pages/radiology/input_result', $data);
    }

    public function storeResult(string $uuid)
    {
        $order = $this->radiologyService->getByUuid($uuid);

        if ($order === null) {
            return redirect()->to('/radiology')->with('error', 'Order radiologi tidak ditemukan.');
        }

        $rules = [
            'result_text' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id') ?? 1;

        if (!$this->radiologyService->inputResult(
            $uuid,
            $this->request->getPost('result_text'),
            $this->request->getPost('impression'),
            $userId
        )) {
            return redirect()->back()->with('error', 'Gagal menyimpan hasil radiologi.');
        }

        return redirect()->to('/radiology/' . $uuid)->with('success', 'Hasil radiologi berhasil disimpan.');
    }

    public function uploadImage(string $uuid)
    {
        $file = $this->request->getFile('image');

        if ($file === null || !$file->isValid()) {
            return redirect()->back()->with('error', 'File gambar tidak valid.');
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/dicom'];
        if (!in_array($file->getClientMimeType(), $allowedTypes)) {
            return redirect()->back()->with('error', 'Tipe file tidak diizinkan. Gunakan JPEG, PNG, GIF, atau DICOM.');
        }

        if (!$this->radiologyService->uploadImage($uuid, $file, $this->request->getPost('description'))) {
            return redirect()->back()->with('error', 'Gagal mengupload gambar.');
        }

        return redirect()->to('/radiology/' . $uuid)->with('success', 'Gambar berhasil diupload.');
    }

    public function cancel(string $uuid)
    {
        if (!$this->radiologyService->cancel($uuid)) {
            return redirect()->back()->with('error', 'Gagal membatalkan order radiologi.');
        }

        return redirect()->back()->with('success', 'Order radiologi berhasil dibatalkan.');
    }
}
