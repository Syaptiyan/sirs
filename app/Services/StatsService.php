<?php

namespace App\Services;

use App\Models\VisitModel;
use App\Models\PaymentModel;
use App\Models\InvoiceModel;
use App\Models\PatientModel;
use App\Models\MedicalRecordModel;
use App\Models\DrugModel;

class StatsService
{
    private VisitModel $visitModel;
    private PaymentModel $paymentModel;
    private InvoiceModel $invoiceModel;
    private PatientModel $patientModel;
    private MedicalRecordModel $medicalRecordModel;
    private DrugModel $drugModel;

    public function __construct()
    {
        $this->visitModel = new VisitModel();
        $this->paymentModel = new PaymentModel();
        $this->invoiceModel = new InvoiceModel();
        $this->patientModel = new PatientModel();
        $this->medicalRecordModel = new MedicalRecordModel();
        $this->drugModel = new DrugModel();
    }

    public function getVisitStats(?string $period = 'daily', ?int $days = 30): array
    {
        $startDate = date('Y-m-d', strtotime("-{$days} days"));

        $visits = $this->visitModel
            ->where('visit_date >=', $startDate)
            ->where('deleted_at', null)
            ->findAll();

        $stats = [];
        $labels = [];
        $values = [];

        if ($period === 'daily') {
            for ($i = 0; $i < $days; $i++) {
                $date = date('Y-m-d', strtotime("-{$i} days"));
                $labels[] = date('d M', strtotime($date));
                $count = 0;
                foreach ($visits as $visit) {
                    if ($visit->visit_date === $date) {
                        $count++;
                    }
                }
                $values[] = $count;
            }
            $labels = array_reverse($labels);
            $values = array_reverse($values);
        } elseif ($period === 'weekly') {
            $weeks = ceil($days / 7);
            for ($i = 0; $i < $weeks; $i++) {
                $weekStart = date('Y-m-d', strtotime("-{$i} weeks monday"));
                $weekEnd = date('Y-m-d', strtotime("-{$i} weeks sunday"));
                $labels[] = date('d M', strtotime($weekStart));
                $count = 0;
                foreach ($visits as $visit) {
                    if ($visit->visit_date >= $weekStart && $visit->visit_date <= $weekEnd) {
                        $count++;
                    }
                }
                $values[] = $count;
            }
            $labels = array_reverse($labels);
            $values = array_reverse($values);
        } elseif ($period === 'monthly') {
            $months = ceil($days / 30);
            for ($i = 0; $i < $months; $i++) {
                $monthStart = date('Y-m-01', strtotime("-{$i} months"));
                $monthEnd = date('Y-m-t', strtotime("-{$i} months"));
                $labels[] = date('M Y', strtotime($monthStart));
                $count = 0;
                foreach ($visits as $visit) {
                    if ($visit->visit_date >= $monthStart && $visit->visit_date <= $monthEnd) {
                        $count++;
                    }
                }
                $values[] = $count;
            }
            $labels = array_reverse($labels);
            $values = array_reverse($values);
        }

        $statusCounts = [];
        foreach ($visits as $visit) {
            $status = $visit->status ?? 'unknown';
            $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'total' => count($visits),
            'by_status' => $statusCounts,
            'period' => $period,
            'days' => $days,
        ];
    }

    public function getDiseaseStats(?int $limit = 10): array
    {
        $records = $this->medicalRecordModel
            ->select('diagnosis, COUNT(*) as count')
            ->where('diagnosis IS NOT NULL')
            ->where('diagnosis !=', '')
            ->groupBy('diagnosis')
            ->orderBy('count', 'DESC')
            ->limit($limit)
            ->findAll();

        $labels = [];
        $values = [];

        foreach ($records as $record) {
            $labels[] = $record->diagnosis;
            $values[] = (int) $record->count;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'total_records' => array_sum($values),
            'limit' => $limit,
        ];
    }

    public function getRevenueStats(?string $period = 'monthly', ?int $months = 12): array
    {
        $startDate = date('Y-m-d', strtotime("-{$months} months"));

        $payments = $this->paymentModel
            ->where('payment_date >=', $startDate)
            ->findAll();

        $labels = [];
        $values = [];

        if ($period === 'daily') {
            $days = $months * 30;
            for ($i = 0; $i < $days; $i++) {
                $date = date('Y-m-d', strtotime("-{$i} days"));
                $labels[] = date('d M', strtotime($date));
                $amount = 0;
                foreach ($payments as $payment) {
                    if (date('Y-m-d', strtotime($payment->payment_date)) === $date) {
                        $amount += (float) $payment->amount;
                    }
                }
                $values[] = $amount;
            }
            $labels = array_reverse($labels);
            $values = array_reverse($values);
        } elseif ($period === 'weekly') {
            $weeks = $months * 4;
            for ($i = 0; $i < $weeks; $i++) {
                $weekStart = date('Y-m-d', strtotime("-{$i} weeks monday"));
                $weekEnd = date('Y-m-d', strtotime("-{$i} weeks sunday"));
                $labels[] = date('d M', strtotime($weekStart));
                $amount = 0;
                foreach ($payments as $payment) {
                    $payDate = date('Y-m-d', strtotime($payment->payment_date));
                    if ($payDate >= $weekStart && $payDate <= $weekEnd) {
                        $amount += (float) $payment->amount;
                    }
                }
                $values[] = $amount;
            }
            $labels = array_reverse($labels);
            $values = array_reverse($values);
        } elseif ($period === 'monthly') {
            for ($i = 0; $i < $months; $i++) {
                $monthStart = date('Y-m-01', strtotime("-{$i} months"));
                $monthEnd = date('Y-m-t', strtotime("-{$i} months"));
                $labels[] = date('M Y', strtotime($monthStart));
                $amount = 0;
                foreach ($payments as $payment) {
                    $payDate = date('Y-m-d', strtotime($payment->payment_date));
                    if ($payDate >= $monthStart && $payDate <= $monthEnd) {
                        $amount += (float) $payment->amount;
                    }
                }
                $values[] = $amount;
            }
            $labels = array_reverse($labels);
            $values = array_reverse($values);
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
            'average' => count($values) > 0 ? array_sum($values) / count($values) : 0,
            'period' => $period,
            'months' => $months,
        ];
    }

    public function getDashboardStats(): array
    {
        $today = date('Y-m-d');
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');

        $todayVisits = $this->visitModel
            ->where('visit_date', $today)
            ->where('deleted_at', null)
            ->countAllResults();

        $monthVisits = $this->visitModel
            ->where('visit_date >=', $monthStart)
            ->where('visit_date <=', $monthEnd)
            ->where('deleted_at', null)
            ->countAllResults();

        $todayRevenue = $this->paymentModel
            ->selectSum('amount')
            ->where('payment_date', $today)
            ->first();

        $monthRevenue = $this->paymentModel
            ->selectSum('amount')
            ->where('payment_date >=', $monthStart)
            ->where('payment_date <=', $monthEnd)
            ->first();

        $totalPatients = $this->patientModel->countAll();

        $pendingInvoices = $this->invoiceModel
            ->where('status', 'unpaid')
            ->countAllResults();

        $lowStockDrugs = $this->drugModel
            ->where('stock <=', 'minimum_stock', false)
            ->countAllResults();

        $visitTrend = $this->getVisitStats('daily', 7);
        $revenueTrend = $this->getRevenueStats('monthly', 6);
        $topDiseases = $this->getDiseaseStats(5);

        return [
            'today' => [
                'visits' => $todayVisits,
                'revenue' => (float) ($todayRevenue->amount ?? 0),
            ],
            'month' => [
                'visits' => $monthVisits,
                'revenue' => (float) ($monthRevenue->amount ?? 0),
            ],
            'total_patients' => $totalPatients,
            'pending_invoices' => $pendingInvoices,
            'low_stock_drugs' => $lowStockDrugs,
            'visit_trend' => $visitTrend,
            'revenue_trend' => $revenueTrend,
            'top_diseases' => $topDiseases,
        ];
    }
}
