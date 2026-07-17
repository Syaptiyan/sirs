<?php

namespace App\Services;

use App\Models\VisitModel;
use App\Models\PaymentModel;
use App\Models\InvoiceModel;
use App\Models\DrugModel;
use App\Models\PrescriptionModel;
use App\Models\LabOrderModel;
use App\Models\WarehouseItemModel;
use App\Models\PolyclinicModel;
use App\Models\PatientModel;

class ReportService
{
    private VisitModel $visitModel;
    private PaymentModel $paymentModel;
    private InvoiceModel $invoiceModel;
    private DrugModel $drugModel;
    private PrescriptionModel $prescriptionModel;
    private LabOrderModel $labOrderModel;
    private WarehouseItemModel $warehouseItemModel;
    private PolyclinicModel $polyclinicModel;
    private PatientModel $patientModel;

    public function __construct()
    {
        $this->visitModel = new VisitModel();
        $this->paymentModel = new PaymentModel();
        $this->invoiceModel = new InvoiceModel();
        $this->drugModel = new DrugModel();
        $this->prescriptionModel = new PrescriptionModel();
        $this->labOrderModel = new LabOrderModel();
        $this->warehouseItemModel = new WarehouseItemModel();
        $this->polyclinicModel = new PolyclinicModel();
        $this->patientModel = new PatientModel();
    }

    public function getVisitReport(?string $dateFrom = null, ?string $dateTo = null, ?int $polyclinicId = null): array
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $builder = $this->visitModel
            ->select('visits.*, patients.name as patient_name, patients.mr_number, doctors.name as doctor_name, polyclinics.name as polyclinic_name')
            ->join('patients', 'patients.id = visits.patient_id')
            ->join('users as doctors', 'doctors.id = visits.doctor_id')
            ->join('polyclinics', 'polyclinics.id = visits.polyclinic_id')
            ->where('visits.visit_date >=', $dateFrom)
            ->where('visits.visit_date <=', $dateTo)
            ->where('visits.deleted_at', null);

        if ($polyclinicId) {
            $builder->where('visits.polyclinic_id', $polyclinicId);
        }

        $visits = $builder->orderBy('visits.visit_date', 'DESC')->findAll();

        $summary = [
            'total_visits' => count($visits),
            'completed' => 0,
            'cancelled' => 0,
            'in_progress' => 0,
            'by_polyclinic' => [],
            'by_day' => [],
        ];

        foreach ($visits as $visit) {
            $summary[$visit->status] = ($summary[$visit->status] ?? 0) + 1;

            $polyName = $visit->polyclinics_name ?? 'Unknown';
            $summary['by_polyclinic'][$polyName] = ($summary['by_polyclinic'][$polyName] ?? 0) + 1;

            $day = $visit->visit_date;
            $summary['by_day'][$day] = ($summary['by_day'][$day] ?? 0) + 1;
        }

        return [
            'visits' => $visits,
            'summary' => $summary,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'polyclinic_id' => $polyclinicId,
        ];
    }

    public function getRevenueReport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $payments = $this->paymentModel
            ->select('payments.*, patients.name as patient_name, patients.mr_number')
            ->join('patients', 'patients.id = payments.patient_id')
            ->where('payments.payment_date >=', $dateFrom)
            ->where('payments.payment_date <=', $dateTo)
            ->orderBy('payments.payment_date', 'DESC')
            ->findAll();

        $summary = [
            'total_revenue' => 0,
            'total_transactions' => count($payments),
            'by_method' => [],
            'by_day' => [],
        ];

        foreach ($payments as $payment) {
            $summary['total_revenue'] += (float) $payment->amount;

            $method = $payment->payment_method_id ?? 'Unknown';
            $summary['by_method'][$method] = ($summary['by_method'][$method] ?? 0) + (float) $payment->amount;

            $day = $payment->payment_date;
            $summary['by_day'][$day] = ($summary['by_day'][$day] ?? 0) + (float) $payment->amount;
        }

        return [
            'payments' => $payments,
            'summary' => $summary,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];
    }

    public function getPharmacyReport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $prescriptions = $this->prescriptionModel
            ->select('prescriptions.*, patients.name as patient_name, patients.mr_number')
            ->join('patients', 'patients.id = prescriptions.patient_id')
            ->where('prescriptions.created_at >=', $dateFrom . ' 00:00:00')
            ->where('prescriptions.created_at <=', $dateTo . ' 23:59:59')
            ->orderBy('prescriptions.created_at', 'DESC')
            ->findAll();

        $summary = [
            'total_prescriptions' => count($prescriptions),
            'dispensed' => 0,
            'pending' => 0,
            'by_day' => [],
        ];

        foreach ($prescriptions as $prescription) {
            $status = $prescription->status ?? 'pending';
            $summary[$status] = ($summary[$status] ?? 0) + 1;

            $day = date('Y-m-d', strtotime($prescription->created_at));
            $summary['by_day'][$day] = ($summary['by_day'][$day] ?? 0) + 1;
        }

        $lowStockDrugs = $this->drugModel
            ->where('stock <=', 'minimum_stock', false)
            ->findAll();

        $expiringDrugs = $this->drugModel
            ->where('expiry_date <=', date('Y-m-d', strtotime('+3 months')))
            ->where('expiry_date >=', date('Y-m-d'))
            ->findAll();

        return [
            'prescriptions' => $prescriptions,
            'summary' => $summary,
            'low_stock' => $lowStockDrugs,
            'expiring' => $expiringDrugs,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];
    }

    public function getLabReport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $labOrders = $this->labOrderModel
            ->select('lab_orders.*, patients.name as patient_name, patients.mr_number')
            ->join('patients', 'patients.id = lab_orders.patient_id')
            ->where('lab_orders.created_at >=', $dateFrom . ' 00:00:00')
            ->where('lab_orders.created_at <=', $dateTo . ' 23:59:59')
            ->orderBy('lab_orders.created_at', 'DESC')
            ->findAll();

        $summary = [
            'total_orders' => count($labOrders),
            'completed' => 0,
            'pending' => 0,
            'in_progress' => 0,
            'by_day' => [],
        ];

        foreach ($labOrders as $order) {
            $status = $order->status ?? 'pending';
            $summary[$status] = ($summary[$status] ?? 0) + 1;

            $day = date('Y-m-d', strtotime($order->created_at));
            $summary['by_day'][$day] = ($summary['by_day'][$day] ?? 0) + 1;
        }

        return [
            'lab_orders' => $labOrders,
            'summary' => $summary,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];
    }

    public function getInventoryReport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $dateFrom = $dateFrom ?? date('Y-m-01');
        $dateTo = $dateTo ?? date('Y-m-d');

        $items = $this->warehouseItemModel->findAll();

        $summary = [
            'total_items' => count($items),
            'low_stock' => 0,
            'out_of_stock' => 0,
            'total_value' => 0,
            'by_category' => [],
        ];

        foreach ($items as $item) {
            if ($item->stock <= 0) {
                $summary['out_of_stock']++;
            } elseif ($item->stock <= ($item->minimum_stock ?? 0)) {
                $summary['low_stock']++;
            }

            $summary['total_value'] += ($item->stock ?? 0) * ($item->price ?? 0);

            $category = $item->category ?? 'Uncategorized';
            $summary['by_category'][$category] = ($summary['by_category'][$category] ?? 0) + 1;
        }

        return [
            'items' => $items,
            'summary' => $summary,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];
    }

    public function exportToPdf(array $data, string $title): string
    {
        $html = $this->generatePdfHtml($data, $title);

        $filename = strtolower(str_replace(' ', '_', $title)) . '_' . date('Y-m-d_His') . '.html';
        $filepath = WRITEPATH . 'exports/' . $filename;

        $dir = dirname($filepath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($filepath, $html);

        return $filepath;
    }

    public function exportToExcel(array $data, string $title): string
    {
        $csv = $this->generateCsvContent($data, $title);

        $filename = strtolower(str_replace(' ', '_', $title)) . '_' . date('Y-m-d_His') . '.csv';
        $filepath = WRITEPATH . 'exports/' . $filename;

        $dir = dirname($filepath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($filepath, $csv);

        return $filepath;
    }

    private function generatePdfHtml(array $data, string $title): string
    {
        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>' . esc($title) . '</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; font-size: 18px; }
        h2 { font-size: 14px; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .summary { margin: 20px 0; }
        .summary-item { display: inline-block; margin-right: 30px; }
        .summary-label { font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <h1>' . esc($title) . '</h1>
    <p style="text-align: center;">Tanggal Cetak: ' . date('d/m/Y H:i:s') . '</p>';

        if (isset($data['summary'])) {
            $html .= '<div class="summary"><h2>Ringkasan</h2>';
            foreach ($data['summary'] as $key => $value) {
                if (!is_array($value)) {
                    $label = ucwords(str_replace('_', ' ', $key));
                    $formattedValue = is_numeric($value) && strpos($key, 'revenue') !== false
                        ? 'Rp ' . number_format($value, 0, ',', '.')
                        : $value;
                    $html .= '<div class="summary-item"><span class="summary-label">' . $label . ':</span> ' . $formattedValue . '</div>';
                }
            }
            $html .= '</div>';
        }

        $mainData = null;
        foreach ($data as $key => $value) {
            if (is_array($value) && !empty($value) && isset($value[0]) && is_object($value[0])) {
                $mainData = $value;
                break;
            }
        }

        if ($mainData) {
            $html .= '<table><thead><tr>';
            $first = $mainData[0];
            foreach (get_object_vars($first) as $attr => $val) {
                if (!in_array($attr, ['id', 'uuid', 'deleted_at', 'updated_at'])) {
                    $html .= '<th>' . esc(ucwords(str_replace('_', ' ', $attr))) . '</th>';
                }
            }
            $html .= '</tr></thead><tbody>';

            foreach ($mainData as $row) {
                $html .= '<tr>';
                foreach (get_object_vars($row) as $attr => $val) {
                    if (!in_array($attr, ['id', 'uuid', 'deleted_at', 'updated_at'])) {
                        $html .= '<td>' . esc($val ?? '-') . '</td>';
                    }
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        }

        $html .= '<div class="footer">Dicetak oleh: ' . (session()->get('name') ?? 'System') . '</div>';
        $html .= '</body></html>';

        return $html;
    }

    private function generateCsvContent(array $data, string $title): string
    {
        $csv = "sep=,\n";
        $csv .= '"' . $title . '"\n';
        $csv .= '"Tanggal",' . date('d/m/Y H:i:s') . "\n\n";

        if (isset($data['summary'])) {
            $csv .= '"Ringkasan"\n';
            foreach ($data['summary'] as $key => $value) {
                if (!is_array($value)) {
                    $label = ucwords(str_replace('_', ' ', $key));
                    $csv .= '"' . $label . '","' . $value . '"\n';
                }
            }
            $csv .= "\n";
        }

        $mainData = null;
        foreach ($data as $key => $value) {
            if (is_array($value) && !empty($value) && isset($value[0]) && is_object($value[0])) {
                $mainData = $value;
                break;
            }
        }

        if ($mainData) {
            $first = $mainData[0];
            $headers = [];
            foreach (get_object_vars($first) as $attr => $val) {
                if (!in_array($attr, ['id', 'uuid', 'deleted_at', 'updated_at'])) {
                    $headers[] = ucwords(str_replace('_', ' ', $attr));
                }
            }
            $csv .= implode(',', array_map(fn($h) => '"' . $h . '"', $headers)) . "\n";

            foreach ($mainData as $row) {
                $values = [];
                foreach (get_object_vars($row) as $attr => $val) {
                    if (!in_array($attr, ['id', 'uuid', 'deleted_at', 'updated_at'])) {
                        $values[] = '"' . str_replace('"', '""', $val ?? '-') . '"';
                    }
                }
                $csv .= implode(',', $values) . "\n";
            }
        }

        return $csv;
    }
}
