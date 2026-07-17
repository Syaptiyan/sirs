<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\PatientModel;
use App\Models\VisitModel;
use App\Models\InvoiceModel;
use App\Models\PaymentModel;
use App\Models\DrugModel;
use App\Models\LabOrderModel;

class DashboardService
{
    private UserModel $userModel;
    private PatientModel $patientModel;
    private VisitModel $visitModel;
    private InvoiceModel $invoiceModel;
    private PaymentModel $paymentModel;
    private DrugModel $drugModel;
    private LabOrderModel $labOrderModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->patientModel = new PatientModel();
        $this->visitModel = new VisitModel();
        $this->invoiceModel = new InvoiceModel();
        $this->paymentModel = new PaymentModel();
        $this->drugModel = new DrugModel();
        $this->labOrderModel = new LabOrderModel();
    }

    public function getAdminStats(): array
    {
        return [
            'total_users' => $this->userModel->countAll(),
            'total_patients' => $this->patientModel->countAll(),
            'today_visits' => $this->getTodayVisits(),
            'today_revenue' => $this->getTodayRevenue(),
            'recent_activities' => $this->getRecentActivities(),
        ];
    }

    public function getDoctorStats(int $doctorId): array
    {
        return [
            'today_schedule' => $this->getDoctorSchedule($doctorId),
            'waiting_patients' => $this->getWaitingPatients($doctorId),
            'pending_prescriptions' => $this->getPendingPrescriptions($doctorId),
            'today_visits' => $this->getDoctorTodayVisits($doctorId),
        ];
    }

    public function getNurseStats(): array
    {
        return [
            'today_tasks' => $this->getTodayTasks(),
            'inpatients' => $this->getInpatients(),
            'pending_assessments' => $this->getPendingAssessments(),
        ];
    }

    public function getCashierStats(): array
    {
        return [
            'pending_invoices' => $this->getPendingInvoices(),
            'today_payments' => $this->getTodayPayments(),
            'today_revenue' => $this->getTodayRevenue(),
        ];
    }

    public function getPharmacyStats(): array
    {
        return [
            'pending_prescriptions' => $this->getPharmacyPendingPrescriptions(),
            'low_stock_drugs' => $this->getLowStockDrugs(),
            'expiring_drugs' => $this->getExpiringDrugs(),
        ];
    }

    public function getLabStats(): array
    {
        return [
            'pending_orders' => $this->getPendingLabOrders(),
            'completed_today' => $this->getCompletedLabOrders(),
        ];
    }

    public function getManagementStats(): array
    {
        return [
            'total_patients' => $this->patientModel->countAll(),
            'today_visits' => $this->getTodayVisits(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'visit_trend' => $this->getVisitTrend(),
        ];
    }

    private function getTodayVisits(): int
    {
        return $this->visitModel
            ->where('visit_date', date('Y-m-d'))
            ->countAllResults();
    }

    private function getTodayRevenue(): float
    {
        $result = $this->paymentModel
            ->selectSum('amount')
            ->where('DATE(payment_date)', date('Y-m-d'))
            ->first();

        return $result->amount ?? 0;
    }

    private function getRecentActivities(): array
    {
        // TODO: Implement with audit log
        return [];
    }

    private function getDoctorSchedule(int $doctorId): array
    {
        // TODO: Implement with doctor schedule model
        return [];
    }

    private function getWaitingPatients(int $doctorId): int
    {
        return $this->visitModel
            ->where('doctor_id', $doctorId)
            ->where('visit_date', date('Y-m-d'))
            ->where('status', 'waiting')
            ->countAllResults();
    }

    private function getPendingPrescriptions(int $doctorId): int
    {
        // TODO: Implement with prescription model
        return 0;
    }

    private function getDoctorTodayVisits(int $doctorId): int
    {
        return $this->visitModel
            ->where('doctor_id', $doctorId)
            ->where('visit_date', date('Y-m-d'))
            ->countAllResults();
    }

    private function getTodayTasks(): array
    {
        // TODO: Implement
        return [];
    }

    private function getInpatients(): int
    {
        return $this->visitModel
            ->where('visit_type', 'inpatient')
            ->where('status', 'in_progress')
            ->countAllResults();
    }

    private function getPendingAssessments(): int
    {
        // TODO: Implement
        return 0;
    }

    private function getPendingInvoices(): int
    {
        return $this->invoiceModel
            ->where('status', 'unpaid')
            ->countAllResults();
    }

    private function getTodayPayments(): int
    {
        return $this->paymentModel
            ->where('DATE(payment_date)', date('Y-m-d'))
            ->countAllResults();
    }

    private function getPharmacyPendingPrescriptions(): int
    {
        // TODO: Implement
        return 0;
    }

    private function getLowStockDrugs(): array
    {
        // TODO: Implement
        return [];
    }

    private function getExpiringDrugs(): array
    {
        // TODO: Implement
        return [];
    }

    private function getPendingLabOrders(): int
    {
        // TODO: Implement
        return 0;
    }

    private function getCompletedLabOrders(): int
    {
        // TODO: Implement
        return 0;
    }

    private function getMonthlyRevenue(): float
    {
        $result = $this->paymentModel
            ->selectSum('amount')
            ->where('MONTH(payment_date)', date('m'))
            ->where('YEAR(payment_date)', date('Y'))
            ->first();

        return $result->amount ?? 0;
    }

    private function getVisitTrend(): array
    {
        // TODO: Implement
        return [];
    }
}
