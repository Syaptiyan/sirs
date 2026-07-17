<?php

namespace App\Services\Payment;

use App\Models\PaymentModel;
use App\Models\PaymentMethodModel;
use App\Models\PaymentReceiptModel;
use App\Models\InvoiceModel;
use App\Services\Billing\BillingService;

class PaymentService
{
    private PaymentModel $paymentModel;
    private PaymentMethodModel $paymentMethodModel;
    private PaymentReceiptModel $paymentReceiptModel;
    private InvoiceModel $invoiceModel;
    private BillingService $billingService;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->paymentMethodModel = new PaymentMethodModel();
        $this->paymentReceiptModel = new PaymentReceiptModel();
        $this->invoiceModel = new InvoiceModel();
        $this->billingService = new BillingService();
    }

    public function processPayment(string $invoiceUuid, int $paymentMethodId, float $amount, ?string $referenceNumber = null, ?string $notes = null): ?object
    {
        $invoice = $this->invoiceModel->where('uuid', $invoiceUuid)->first();
        if ($invoice === null) {
            return null;
        }

        if ($invoice->status === 'paid' || $invoice->status === 'cancelled') {
            return null;
        }

        $paymentMethod = $this->paymentMethodModel->find($paymentMethodId);
        if ($paymentMethod === null || !$paymentMethod->is_active) {
            return null;
        }

        $paymentNumber = $this->generatePaymentNumber();

        $data = [
            'payment_number'    => $paymentNumber,
            'invoice_id'        => $invoice->id,
            'patient_id'        => $invoice->patient_id,
            'payment_method_id' => $paymentMethodId,
            'amount'            => $amount,
            'payment_date'      => date('Y-m-d'),
            'reference_number'  => $referenceNumber,
            'notes'             => $notes,
            'created_by'        => session()->get('user_id'),
        ];

        $id = $this->paymentModel->insert($data);
        if ($id === false) {
            return null;
        }

        $this->billingService->updatePaidAmount($invoice->id, $amount);

        $payment = $this->paymentModel->find($id);
        $this->generateReceipt($id);

        return $payment;
    }

    public function getByUuid(string $uuid): ?object
    {
        $builder = $this->paymentModel->builder();
        $builder->join('invoices', 'invoices.id = payments.invoice_id', 'left');
        $builder->join('patients', 'patients.id = payments.patient_id', 'left');
        $builder->join('payment_methods', 'payment_methods.id = payments.payment_method_id', 'left');
        $builder->select('payments.*, invoices.invoice_number, patients.name as patient_name, patients.mrn, payment_methods.name as payment_method_name, payment_methods.code as payment_method_code');
        $builder->where('payments.uuid', $uuid);

        return $builder->get()->getRow() ?: null;
    }

    public function getPayments(?int $invoiceId = null, ?string $dateFrom = null, ?string $dateTo = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->paymentModel->builder();
        $builder->join('invoices', 'invoices.id = payments.invoice_id', 'left');
        $builder->join('patients', 'patients.id = payments.patient_id', 'left');
        $builder->join('payment_methods', 'payment_methods.id = payments.payment_method_id', 'left');
        $builder->select('payments.*, invoices.invoice_number, patients.name as patient_name, patients.mrn, payment_methods.name as payment_method_name');

        if ($invoiceId !== null) {
            $builder->where('payments.invoice_id', $invoiceId);
        }

        if ($dateFrom !== null) {
            $builder->where('payments.payment_date >=', $dateFrom);
        }

        if ($dateTo !== null) {
            $builder->where('payments.payment_date <=', $dateTo);
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $payments = $builder
            ->orderBy('payments.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'payments'   => $payments,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => (int) ceil($total / $perPage),
        ];
    }

    public function generatePaymentNumber(): string
    {
        $prefix = 'PAY-' . date('Ymd') . '-';
        $lastPayment = $this->paymentModel
            ->like('payment_number', $prefix, 'after')
            ->orderBy('payment_number', 'DESC')
            ->first();

        if ($lastPayment === null) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastPayment->payment_number, -4);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function generateReceipt(int $paymentId): ?object
    {
        $payment = $this->paymentModel->find($paymentId);
        if ($payment === null) {
            return null;
        }

        $existing = $this->paymentReceiptModel->where('payment_id', $paymentId)->first();
        if ($existing !== null) {
            return $existing;
        }

        $receiptNumber = 'REC-' . date('Ymd') . '-' . str_pad($paymentId, 4, '0', STR_PAD_LEFT);

        $data = [
            'payment_id'     => $paymentId,
            'receipt_number' => $receiptNumber,
        ];

        $id = $this->paymentReceiptModel->insert($data);
        if ($id === false) {
            return null;
        }

        return $this->paymentReceiptModel->find($id);
    }

    public function getPaymentMethods(): array
    {
        return $this->paymentMethodModel->where('is_active', true)->findAll();
    }

    public function getReceiptByPaymentUuid(string $paymentUuid): ?object
    {
        $payment = $this->paymentModel->where('uuid', $paymentUuid)->first();
        if ($payment === null) {
            return null;
        }

        return $this->paymentReceiptModel->where('payment_id', $payment->id)->first();
    }
}
