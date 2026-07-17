<?php

namespace App\Services\Lab;

use App\Models\LabOrderModel;
use App\Models\LabOrderItemModel;
use App\Models\LabResultModel;
use App\Models\LabTemplateModel;

class LabService
{
    private LabOrderModel $orderModel;
    private LabOrderItemModel $itemModel;
    private LabResultModel $resultModel;
    private LabTemplateModel $templateModel;

    public function __construct()
    {
        $this->orderModel = new LabOrderModel();
        $this->itemModel = new LabOrderItemModel();
        $this->resultModel = new LabResultModel();
        $this->templateModel = new LabTemplateModel();
    }

    public function createOrder(int $visitId, int $patientId, int $doctorId, array $items): ?object
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $orderNumber = $this->generateOrderNumber();

        $orderId = $this->orderModel->insert([
            'order_number' => $orderNumber,
            'visit_id'     => $visitId,
            'patient_id'   => $patientId,
            'doctor_id'    => $doctorId,
            'order_date'   => date('Y-m-d'),
            'status'       => 'pending',
            'notes'        => $items['notes'] ?? null,
        ]);

        if ($orderId === false) {
            $db->transRollback();
            return null;
        }

        foreach ($items['details'] as $item) {
            $template = null;
            if (!empty($item['template_id'])) {
                $template = $this->templateModel->find($item['template_id']);
            }

            $itemId = $this->itemModel->insert([
                'order_id'       => $orderId,
                'template_id'    => $item['template_id'] ?? null,
                'parameter_name' => $item['parameter_name'] ?? ($template->name ?? ''),
                'unit'           => $item['unit'] ?? null,
                'normal_range'   => $item['normal_range'] ?? null,
            ]);

            if ($itemId === false) {
                $db->transRollback();
                return null;
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return null;
        }

        return $this->orderModel->find($orderId);
    }

    public function getByUuid(string $uuid): ?object
    {
        $order = $this->orderModel
            ->select('lab_orders.*')
            ->select('patients.name as patient_name, patients.mrn, patients.gender, patients.date_of_birth')
            ->select('doctors.name as doctor_name')
            ->select('visits.visit_number')
            ->join('patients', 'patients.id = lab_orders.patient_id', 'left')
            ->join('doctors', 'doctors.id = lab_orders.doctor_id', 'left')
            ->join('visits', 'visits.id = lab_orders.visit_id', 'left')
            ->where('lab_orders.uuid', $uuid)
            ->first();

        if ($order === null) {
            return null;
        }

        $order->items = $this->itemModel
            ->select('lab_order_items.*')
            ->select('lab_templates.name as template_name, lab_templates.category as template_category')
            ->join('lab_templates', 'lab_templates.id = lab_order_items.template_id', 'left')
            ->where('order_id', $order->id)
            ->findAll();

        $order->results = $this->resultModel
            ->where('order_id', $order->id)
            ->findAll();

        return $order;
    }

    public function getOrders(?string $status = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->orderModel
            ->select('lab_orders.*')
            ->select('patients.name as patient_name, patients.mrn')
            ->select('doctors.name as doctor_name')
            ->join('patients', 'patients.id = lab_orders.patient_id', 'left')
            ->join('doctors', 'doctors.id = lab_orders.doctor_id', 'left')
            ->orderBy('lab_orders.created_at', 'DESC');

        if ($status !== null && $status !== '') {
            $builder->where('lab_orders.status', $status);
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
            'totalPages' => ceil($total / $perPage),
        ];
    }

    public function inputResults(string $orderUuid, array $results, int $userId): bool
    {
        $order = $this->orderModel->where('uuid', $orderUuid)->first();
        if ($order === null || !in_array($order->status, ['pending', 'collected', 'processing'])) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($results as $item) {
            if (empty($item['id'])) {
                continue;
            }

            $updateResult = $this->itemModel->update($item['id'], [
                'result_value' => $item['result_value'] ?? null,
                'notes'        => $item['notes'] ?? null,
            ]);

            if ($updateResult === false) {
                $db->transRollback();
                return false;
            }
        }

        $this->resultModel->insert([
            'order_id'    => $order->id,
            'result_date' => date('Y-m-d'),
            'result_by'   => $userId,
            'notes'       => $results['notes'] ?? null,
        ]);

        $this->orderModel->update($order->id, [
            'status' => 'completed',
        ]);

        $db->transComplete();

        return $db->transStatus() !== false;
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
        $prefix = 'LAB-' . $date . '-';

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
