<?php

namespace App\Services\Radiology;

use App\Models\RadiologyOrderModel;
use App\Models\RadiologyResultModel;
use App\Models\RadiologyImageModel;
use App\Models\RadiologyTemplateModel;

class RadiologyService
{
    private RadiologyOrderModel $orderModel;
    private RadiologyResultModel $resultModel;
    private RadiologyImageModel $imageModel;
    private RadiologyTemplateModel $templateModel;

    public function __construct()
    {
        $this->orderModel = new RadiologyOrderModel();
        $this->resultModel = new RadiologyResultModel();
        $this->imageModel = new RadiologyImageModel();
        $this->templateModel = new RadiologyTemplateModel();
    }

    public function createOrder(int $visitId, int $patientId, int $doctorId, ?int $templateId = null, ?string $notes = null): ?object
    {
        $orderNumber = $this->generateOrderNumber();

        $orderId = $this->orderModel->insert([
            'order_number' => $orderNumber,
            'visit_id'     => $visitId,
            'patient_id'   => $patientId,
            'doctor_id'    => $doctorId,
            'template_id'  => $templateId,
            'order_date'   => date('Y-m-d'),
            'status'       => 'pending',
            'notes'        => $notes,
        ]);

        if ($orderId === false) {
            return null;
        }

        return $this->orderModel->find($orderId);
    }

    public function getByUuid(string $uuid): ?object
    {
        $order = $this->orderModel
            ->select('radiology_orders.*')
            ->select('patients.name as patient_name, patients.mrn, patients.gender, patients.date_of_birth')
            ->select('doctors.name as doctor_name')
            ->select('visits.visit_number')
            ->select('radiology_templates.name as template_name, radiology_templates.category as template_category')
            ->join('patients', 'patients.id = radiology_orders.patient_id', 'left')
            ->join('doctors', 'doctors.id = radiology_orders.doctor_id', 'left')
            ->join('visits', 'visits.id = radiology_orders.visit_id', 'left')
            ->join('radiology_templates', 'radiology_templates.id = radiology_orders.template_id', 'left')
            ->where('radiology_orders.uuid', $uuid)
            ->first();

        if ($order === null) {
            return null;
        }

        $order->results = $this->resultModel
            ->select('radiology_results.*')
            ->select('users.name as result_by_name')
            ->join('users', 'users.id = radiology_results.result_by', 'left')
            ->where('order_id', $order->id)
            ->orderBy('radiology_results.result_date', 'DESC')
            ->findAll();

        $order->images = $this->imageModel
            ->where('order_id', $order->id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return $order;
    }

    public function getOrders(?string $status = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->orderModel
            ->select('radiology_orders.*')
            ->select('patients.name as patient_name, patients.mrn')
            ->select('doctors.name as doctor_name')
            ->join('patients', 'patients.id = radiology_orders.patient_id', 'left')
            ->join('doctors', 'doctors.id = radiology_orders.doctor_id', 'left')
            ->orderBy('radiology_orders.created_at', 'DESC');

        if ($status !== null && $status !== '') {
            $builder->where('radiology_orders.status', $status);
        }

        $total = $builder->countAllResults(false);

        $orders = $builder
            ->limit($perPage, ($page - 1) * $perPage)
            ->findAll();

        return [
            'orders'     => $orders,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => (int) ceil($total / $perPage),
        ];
    }

    public function inputResult(string $orderUuid, string $resultText, ?string $impression = null, int $userId): bool
    {
        $order = $this->orderModel->where('uuid', $orderUuid)->first();
        if ($order === null || in_array($order->status, ['completed', 'cancelled'])) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->resultModel->insert([
            'order_id'    => $order->id,
            'result_text' => $resultText,
            'impression'  => $impression,
            'result_date' => date('Y-m-d'),
            'result_by'   => $userId,
        ]);

        $this->orderModel->update($order->id, [
            'status' => 'completed',
        ]);

        $db->transComplete();

        return $db->transStatus() !== false;
    }

    public function uploadImage(string $orderUuid, array $file, ?string $description = null): bool
    {
        $order = $this->orderModel->where('uuid', $orderUuid)->first();
        if ($order === null) {
            return false;
        }

        $uploadPath = FCPATH . 'uploads/radiology/' . $order->order_number . '/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = $file->getRandomName();
        if (!$file->move($uploadPath, $newName)) {
            return false;
        }

        $this->imageModel->insert([
            'order_id'    => $order->id,
            'file_name'   => $file->getClientName(),
            'file_path'   => 'uploads/radiology/' . $order->order_number . '/' . $newName,
            'file_size'   => $file->getClientSize(),
            'description' => $description,
        ]);

        return true;
    }

    public function cancel(string $uuid): bool
    {
        $order = $this->orderModel->where('uuid', $uuid)->first();
        if ($order === null || in_array($order->status, ['completed', 'cancelled'])) {
            return false;
        }

        return $this->orderModel->update($order->id, [
            'status' => 'cancelled',
        ]);
    }

    public function generateOrderNumber(): string
    {
        $date = date('Ymd');
        $prefix = 'RAD-' . $date . '-';

        $lastOrder = $this->orderModel
            ->like('order_number', $prefix, 'after')
            ->orderBy('order_number', 'DESC')
            ->first();

        if ($lastOrder === null) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getTemplates(?string $category = null): array
    {
        $builder = $this->templateModel->where('is_active', true);

        if ($category !== null && $category !== '') {
            $builder->where('category', $category);
        }

        return $builder->orderBy('category', 'ASC')->orderBy('name', 'ASC')->findAll();
    }
}
