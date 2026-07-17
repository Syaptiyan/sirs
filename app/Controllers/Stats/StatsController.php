<?php

namespace App\Controllers\Stats;

use App\Controllers\BaseController;
use App\Services\StatsService;

class StatsController extends BaseController
{
    private StatsService $statsService;

    public function __construct()
    {
        $this->statsService = new StatsService();
    }

    public function index()
    {
        $stats = $this->statsService->getDashboardStats();

        $data = [
            'title' => 'Statistik',
            'stats' => $stats,
        ];

        return view('pages/stats/index', $data);
    }

    public function visits()
    {
        $period = $this->request->getGet('period') ?? 'daily';
        $days = (int) ($this->request->getGet('days') ?? 30);

        $stats = $this->statsService->getVisitStats($period, $days);

        $data = [
            'title' => 'Statistik Kunjungan',
            'stats' => $stats,
            'period' => $period,
            'days' => $days,
        ];

        return view('pages/stats/visits', $data);
    }

    public function diseases()
    {
        $limit = (int) ($this->request->getGet('limit') ?? 10);

        $stats = $this->statsService->getDiseaseStats($limit);

        $data = [
            'title' => 'Statistik Penyakit',
            'stats' => $stats,
            'limit' => $limit,
        ];

        return view('pages/stats/diseases', $data);
    }

    public function revenue()
    {
        $period = $this->request->getGet('period') ?? 'monthly';
        $months = (int) ($this->request->getGet('months') ?? 12);

        $stats = $this->statsService->getRevenueStats($period, $months);

        $data = [
            'title' => 'Statistik Pendapatan',
            'stats' => $stats,
            'period' => $period,
            'months' => $months,
        ];

        return view('pages/stats/revenue', $data);
    }
}
