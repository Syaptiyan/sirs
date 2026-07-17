<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use App\Services\Billing\BillingService;
use App\Models\PatientModel;
use App\Models\VisitModel;

class BillingController extends BaseController
{
    private BillingService $billingService;
    private PatientModel $patientModel;
    private VisitModel $visitModel;

    public function __construct()
    {
        $this->billingService = new BillingService();
        $this->patientModel = new PatientModel();
        $this->visitModel = new VisitModel();
    }

    public function index(): string
    {
        $status = $this->request->getGet('status');
        $patientId = $this->request->getGet('patient_id') ? (int) $this->request->getGet('patient_id') : null;
        $page = (int) $this->request->getGet('page', 1);

        $result = $this->billingService->getInvoices($status, $patientId, $page);

        $data = [
            'title'      => 'Tagihan',
            'invoices'   => $result['invoices'],
            'total'      => $result['total'],
            'page'       => $result['page'],
            'perPage'    => $result['perPage'],
            'totalPages' => $result['totalPages'],
            'status'     => $status,
            'patientId'  => $patientId,
        ];

        return view('pages/billing/index', $data);
    }

    public function show(string $uuid): string
    {
        $invoice = $this->billingService->getByUuid($uuid);

        if ($invoice === null) {
            return redirect()->to('/billing')->with('error', 'Tagihan tidak ditemukan.');
        }

        $items = $this->billingService->getInvoiceItems($invoice->id);

        $data = [
            'title'   => 'Detail Tagihan',
            'invoice' => $invoice,
            'items'   => $items,
        ];

        return view('pages/billing/show', $data);
    }

    public function store()
    {
        $visitId = (int) $this->request->getPost('visit_id');

        $rules = [
            'visit_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $invoice = $this->billingService->generateInvoice($visitId);

        if ($invoice === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat tagihan.');
        }

        return redirect()->to('/billing/' . $invoice->uuid)->with('success', 'Tagihan ' . $invoice->invoice_number . ' berhasil dibuat.');
    }

    public function addItem(string $uuid)
    {
        $itemType = $this->request->getPost('item_type');
        $itemId = (int) $this->request->getPost('item_id');
        $itemName = $this->request->getPost('item_name');
        $quantity = (int) $this->request->getPost('quantity');
        $unitPrice = (float) $this->request->getPost('unit_price');

        $rules = [
            'item_type'  => 'required|in_list[consultation,action,drug,lab,radiology,room]',
            'item_id'    => 'required|integer',
            'item_name'  => 'required|max_length[200]',
            'quantity'   => 'required|integer',
            'unit_price' => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        if (!$this->billingService->addItem($uuid, $itemType, $itemId, $itemName, $quantity, $unitPrice)) {
            return redirect()->back()->with('error', 'Gagal menambahkan item.');
        }

        return redirect()->back()->with('success', 'Item berhasil ditambahkan.');
    }

    public function removeItem(int $id)
    {
        if (!$this->billingService->removeItem($id)) {
            return redirect()->back()->with('error', 'Gagal menghapus item.');
        }

        return redirect()->back()->with('success', 'Item berhasil dihapus.');
    }

    public function applyDiscount(string $uuid)
    {
        $discountAmount = (float) $this->request->getPost('discount_amount');

        $rules = [
            'discount_amount' => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        if (!$this->billingService->applyDiscount($uuid, $discountAmount)) {
            return redirect()->back()->with('error', 'Gagal menerapkan diskon.');
        }

        return redirect()->back()->with('success', 'Diskon berhasil diterapkan.');
    }

    public function print(string $uuid)
    {
        $invoice = $this->billingService->getByUuid($uuid);

        if ($invoice === null) {
            return redirect()->to('/billing')->with('error', 'Tagihan tidak ditemukan.');
        }

        $items = $this->billingService->getInvoiceItems($invoice->id);

        $data = [
            'invoice' => $invoice,
            'items'   => $items,
        ];

        return view('pages/billing/print', $data);
    }
}
