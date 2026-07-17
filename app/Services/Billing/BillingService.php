<?php

namespace App\Services\Billing;

use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\DiscountModel;
use App\Models\VisitModel;

class BillingService
{
    private InvoiceModel $invoiceModel;
    private InvoiceItemModel $invoiceItemModel;
    private DiscountModel $discountModel;
    private VisitModel $visitModel;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->invoiceItemModel = new InvoiceItemModel();
        $this->discountModel = new DiscountModel();
        $this->visitModel = new VisitModel();
    }

    public function generateInvoice(int $visitId): ?object
    {
        $visit = $this->visitModel->find($visitId);
        if ($visit === null) {
            return null;
        }

        $existing = $this->invoiceModel->where('visit_id', $visitId)->first();
        if ($existing !== null) {
            return $existing;
        }

        $invoiceNumber = $this->generateInvoiceNumber();

        $data = [
            'invoice_number'   => $invoiceNumber,
            'visit_id'         => $visitId,
            'patient_id'       => $visit->patient_id,
            'invoice_date'     => date('Y-m-d'),
            'subtotal'         => 0,
            'discount_amount'  => 0,
            'tax_amount'       => 0,
            'total_amount'     => 0,
            'paid_amount'      => 0,
            'remaining_amount' => 0,
            'status'           => 'unpaid',
        ];

        $id = $this->invoiceModel->insert($data);
        if ($id === false) {
            return null;
        }

        return $this->invoiceModel->find($id);
    }

    public function getByUuid(string $uuid): ?object
    {
        $builder = $this->invoiceModel->builder();
        $builder->join('visits', 'visits.id = invoices.visit_id', 'left');
        $builder->join('patients', 'patients.id = invoices.patient_id', 'left');
        $builder->select('invoices.*, visits.visit_number, patients.name as patient_name, patients.mrn');
        $builder->where('invoices.uuid', $uuid);

        return $builder->get()->getRow() ?: null;
    }

    public function getInvoices(?string $status = null, ?int $patientId = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->invoiceModel->builder();
        $builder->join('visits', 'visits.id = invoices.visit_id', 'left');
        $builder->join('patients', 'patients.id = invoices.patient_id', 'left');
        $builder->select('invoices.*, visits.visit_number, patients.name as patient_name, patients.mrn');

        if ($status !== null) {
            $builder->where('invoices.status', $status);
        }

        if ($patientId !== null) {
            $builder->where('invoices.patient_id', $patientId);
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $invoices = $builder
            ->orderBy('invoices.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'invoices'   => $invoices,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => (int) ceil($total / $perPage),
        ];
    }

    public function addItem(string $invoiceUuid, string $itemType, int $itemId, string $itemName, int $quantity, float $unitPrice): bool
    {
        $invoice = $this->invoiceModel->where('uuid', $invoiceUuid)->first();
        if ($invoice === null) {
            return false;
        }

        $data = [
            'invoice_id'  => $invoice->id,
            'item_type'   => $itemType,
            'item_id'     => $itemId,
            'item_name'   => $itemName,
            'quantity'    => $quantity,
            'unit_price'  => $unitPrice,
            'total_price' => $quantity * $unitPrice,
        ];

        $result = $this->invoiceItemModel->insert($data);
        if ($result === false) {
            return false;
        }

        return $this->calculateTotals($invoiceUuid);
    }

    public function removeItem(int $itemId): bool
    {
        $item = $this->invoiceItemModel->find($itemId);
        if ($item === null) {
            return false;
        }

        $invoice = $this->invoiceModel->find($item->invoice_id);
        if ($invoice === null) {
            return false;
        }

        $result = $this->invoiceItemModel->delete($itemId);
        if ($result === false) {
            return false;
        }

        return $this->calculateTotals($invoice->uuid);
    }

    public function applyDiscount(string $invoiceUuid, float $discountAmount): bool
    {
        $invoice = $this->invoiceModel->where('uuid', $invoiceUuid)->first();
        if ($invoice === null) {
            return false;
        }

        $result = $this->invoiceModel->update($invoice->id, [
            'discount_amount' => $discountAmount,
        ]);

        if ($result === false) {
            return false;
        }

        return $this->calculateTotals($invoiceUuid);
    }

    public function calculateTotals(string $invoiceUuid): bool
    {
        $invoice = $this->invoiceModel->where('uuid', $invoiceUuid)->first();
        if ($invoice === null) {
            return false;
        }

        $items = $this->invoiceItemModel->where('invoice_id', $invoice->id)->findAll();
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float) $item->total_price;
        }

        $discountAmount = (float) $invoice->discount_amount;
        $taxAmount = 0;
        $totalAmount = $subtotal - $discountAmount + $taxAmount;
        $remainingAmount = $totalAmount - (float) $invoice->paid_amount;

        $status = 'unpaid';
        if ((float) $invoice->paid_amount > 0) {
            $status = $remainingAmount <= 0 ? 'paid' : 'partial';
        }

        return $this->invoiceModel->update($invoice->id, [
            'subtotal'         => $subtotal,
            'tax_amount'       => $taxAmount,
            'total_amount'     => $totalAmount,
            'remaining_amount' => max(0, $remainingAmount),
            'status'           => $status,
        ]);
    }

    public function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $lastInvoice = $this->invoiceModel
            ->like('invoice_number', $prefix, 'after')
            ->orderBy('invoice_number', 'DESC')
            ->first();

        if ($lastInvoice === null) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getInvoiceItems(int $invoiceId): array
    {
        return $this->invoiceItemModel->where('invoice_id', $invoiceId)->findAll();
    }

    public function updatePaidAmount(int $invoiceId, float $amount): bool
    {
        $invoice = $this->invoiceModel->find($invoiceId);
        if ($invoice === null) {
            return false;
        }

        $newPaidAmount = (float) $invoice->paid_amount + $amount;
        $remainingAmount = (float) $invoice->total_amount - $newPaidAmount;

        $status = 'partial';
        if ($remainingAmount <= 0) {
            $status = 'paid';
        }

        return $this->invoiceModel->update($invoiceId, [
            'paid_amount'      => $newPaidAmount,
            'remaining_amount' => max(0, $remainingAmount),
            'status'           => $status,
        ]);
    }
}
