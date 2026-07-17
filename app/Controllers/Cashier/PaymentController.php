<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use App\Services\Payment\PaymentService;
use App\Services\Billing\BillingService;

class PaymentController extends BaseController
{
    private PaymentService $paymentService;
    private BillingService $billingService;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
        $this->billingService = new BillingService();
    }

    public function index(): string
    {
        $invoiceId = $this->request->getGet('invoice_id') ? (int) $this->request->getGet('invoice_id') : null;
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $page = (int) $this->request->getGet('page', 1);

        $result = $this->paymentService->getPayments($invoiceId, $dateFrom, $dateTo, $page);

        $data = [
            'title'      => 'Pembayaran',
            'payments'   => $result['payments'],
            'total'      => $result['total'],
            'page'       => $result['page'],
            'perPage'    => $result['perPage'],
            'totalPages' => $result['totalPages'],
            'invoiceId'  => $invoiceId,
            'dateFrom'   => $dateFrom,
            'dateTo'     => $dateTo,
        ];

        return view('pages/payments/index', $data);
    }

    public function process()
    {
        if ($this->request->getMethod() === 'GET') {
            $invoiceUuid = $this->request->getGet('invoice_uuid');

            $data = [
                'title'           => 'Proses Pembayaran',
                'invoiceUuid'     => $invoiceUuid,
                'paymentMethods'  => $this->paymentService->getPaymentMethods(),
            ];

            if ($invoiceUuid) {
                $data['invoice'] = $this->billingService->getByUuid($invoiceUuid);
            }

            return view('pages/payments/process', $data);
        }

        $invoiceUuid = $this->request->getPost('invoice_uuid');
        $paymentMethodId = (int) $this->request->getPost('payment_method_id');
        $amount = (float) $this->request->getPost('amount');
        $referenceNumber = $this->request->getPost('reference_number');
        $notes = $this->request->getPost('notes');

        $rules = [
            'invoice_uuid'      => 'required',
            'payment_method_id' => 'required|integer',
            'amount'            => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $payment = $this->paymentService->processPayment($invoiceUuid, $paymentMethodId, $amount, $referenceNumber, $notes);

        if ($payment === null) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses pembayaran.');
        }

        return redirect()->to('/payments/' . $payment->uuid)->with('success', 'Pembayaran ' . $payment->payment_number . ' berhasil diproses.');
    }

    public function show(string $uuid): string
    {
        $payment = $this->paymentService->getByUuid($uuid);

        if ($payment === null) {
            return redirect()->to('/payments')->with('error', 'Pembayaran tidak ditemukan.');
        }

        $receipt = $this->paymentService->getReceiptByPaymentUuid($uuid);

        $data = [
            'title'   => 'Detail Pembayaran',
            'payment' => $payment,
            'receipt' => $receipt,
        ];

        return view('pages/payments/show', $data);
    }

    public function receipt(string $uuid)
    {
        $payment = $this->paymentService->getByUuid($uuid);

        if ($payment === null) {
            return redirect()->to('/payments')->with('error', 'Pembayaran tidak ditemukan.');
        }

        $receipt = $this->paymentService->getReceiptByPaymentUuid($uuid);

        $data = [
            'payment' => $payment,
            'receipt' => $receipt,
        ];

        return view('pages/payments/receipt', $data);
    }
}
