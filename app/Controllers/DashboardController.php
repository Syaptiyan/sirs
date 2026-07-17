<?php

namespace App\Controllers;

use App\Services\DashboardService;

class DashboardController extends BaseController
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $roles = session()->get('roles');

        $data = [
            'title' => 'Dashboard',
            'user' => $this->getUserData($userId),
        ];

        if (in_array('admin', $roles)) {
            $data['stats'] = $this->dashboardService->getAdminStats();
            return view('pages/dashboard/admin', $data);
        }

        if (in_array('doctor', $roles)) {
            $data['stats'] = $this->dashboardService->getDoctorStats($userId);
            return view('pages/dashboard/doctor', $data);
        }

        if (in_array('nurse', $roles)) {
            $data['stats'] = $this->dashboardService->getNurseStats();
            return view('pages/dashboard/nurse', $data);
        }

        if (in_array('cashier', $roles)) {
            $data['stats'] = $this->dashboardService->getCashierStats();
            return view('pages/dashboard/cashier', $data);
        }

        if (in_array('pharmacist', $roles)) {
            $data['stats'] = $this->dashboardService->getPharmacyStats();
            return view('pages/dashboard/pharmacy', $data);
        }

        if (in_array('lab', $roles)) {
            $data['stats'] = $this->dashboardService->getLabStats();
            return view('pages/dashboard/lab', $data);
        }

        if (in_array('management', $roles)) {
            $data['stats'] = $this->dashboardService->getManagementStats();
            return view('pages/dashboard/management', $data);
        }

        // Default dashboard for other roles
        return view('pages/dashboard/default', $data);
    }

    private function getUserData(int $userId): ?object
    {
        $userModel = new \App\Models\UserModel();
        return $userModel->find($userId);
    }
}
