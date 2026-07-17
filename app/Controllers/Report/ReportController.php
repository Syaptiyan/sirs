<?php

namespace App\Controllers\Report;

use App\Controllers\BaseController;
use App\Services\ReportService;
use App\Models\PolyclinicModel;

class ReportController extends BaseController
{
    private ReportService $reportService;
    private PolyclinicModel $polyclinicModel;

    public function __construct()
    {
        $this->reportService = new ReportService();
        $this->polyclinicModel = new PolyclinicModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Laporan',
        ];

        return view('pages/reports/index', $data);
    }

    public function visits()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $polyclinicId = $this->request->getGet('polyclinic_id');

        $report = $this->reportService->getVisitReport($dateFrom, $dateTo, $polyclinicId ? (int) $polyclinicId : null);
        $polyclinics = $this->polyclinicModel->findAll();

        $data = [
            'title' => 'Laporan Kunjungan',
            'report' => $report,
            'polyclinics' => $polyclinics,
        ];

        return view('pages/reports/visits', $data);
    }

    public function revenue()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $report = $this->reportService->getRevenueReport($dateFrom, $dateTo);

        $data = [
            'title' => 'Laporan Pendapatan',
            'report' => $report,
        ];

        return view('pages/reports/revenue', $data);
    }

    public function pharmacy()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $report = $this->reportService->getPharmacyReport($dateFrom, $dateTo);

        $data = [
            'title' => 'Laporan Farmasi',
            'report' => $report,
        ];

        return view('pages/reports/pharmacy', $data);
    }

    public function lab()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $report = $this->reportService->getLabReport($dateFrom, $dateTo);

        $data = [
            'title' => 'Laporan Laboratorium',
            'report' => $report,
        ];

        return view('pages/reports/lab', $data);
    }

    public function inventory()
    {
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $report = $this->reportService->getInventoryReport($dateFrom, $dateTo);

        $data = [
            'title' => 'Laporan Inventaris',
            'report' => $report,
        ];

        return view('pages/reports/inventory', $data);
    }

    public function exportPdf()
    {
        $type = $this->request->getPost('type');
        $dateFrom = $this->request->getPost('date_from');
        $dateTo = $this->request->getPost('date_to');
        $polyclinicId = $this->request->getPost('polyclinic_id');

        $titles = [
            'visits' => 'Laporan Kunjungan',
            'revenue' => 'Laporan Pendapatan',
            'pharmacy' => 'Laporan Farmasi',
            'lab' => 'Laporan Laboratorium',
            'inventory' => 'Laporan Inventaris',
        ];

        $title = $titles[$type] ?? 'Laporan';

        switch ($type) {
            case 'visits':
                $data = $this->reportService->getVisitReport($dateFrom, $dateTo, $polyclinicId ? (int) $polyclinicId : null);
                break;
            case 'revenue':
                $data = $this->reportService->getRevenueReport($dateFrom, $dateTo);
                break;
            case 'pharmacy':
                $data = $this->reportService->getPharmacyReport($dateFrom, $dateTo);
                break;
            case 'lab':
                $data = $this->reportService->getLabReport($dateFrom, $dateTo);
                break;
            case 'inventory':
                $data = $this->reportService->getInventoryReport($dateFrom, $dateTo);
                break;
            default:
                return redirect()->back()->with('error', 'Jenis laporan tidak valid');
        }

        $filepath = $this->reportService->exportToPdf($data, $title);

        return $this->response->download(basename($filepath), file_get_contents($filepath))
            ->setContentType('text/html');
    }

    public function exportExcel()
    {
        $type = $this->request->getPost('type');
        $dateFrom = $this->request->getPost('date_from');
        $dateTo = $this->request->getPost('date_to');
        $polyclinicId = $this->request->getPost('polyclinic_id');

        $titles = [
            'visits' => 'Laporan Kunjungan',
            'revenue' => 'Laporan Pendapatan',
            'pharmacy' => 'Laporan Farmasi',
            'lab' => 'Laporan Laboratorium',
            'inventory' => 'Laporan Inventaris',
        ];

        $title = $titles[$type] ?? 'Laporan';

        switch ($type) {
            case 'visits':
                $data = $this->reportService->getVisitReport($dateFrom, $dateTo, $polyclinicId ? (int) $polyclinicId : null);
                break;
            case 'revenue':
                $data = $this->reportService->getRevenueReport($dateFrom, $dateTo);
                break;
            case 'pharmacy':
                $data = $this->reportService->getPharmacyReport($dateFrom, $dateTo);
                break;
            case 'lab':
                $data = $this->reportService->getLabReport($dateFrom, $dateTo);
                break;
            case 'inventory':
                $data = $this->reportService->getInventoryReport($dateFrom, $dateTo);
                break;
            default:
                return redirect()->back()->with('error', 'Jenis laporan tidak valid');
        }

        $filepath = $this->reportService->exportToExcel($data, $title);

        return $this->response->download(basename($filepath), file_get_contents($filepath))
            ->setContentType('text/csv');
    }
}
