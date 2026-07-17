<?php

namespace App\Controllers;

use App\Services\SettingService;

class SettingController extends BaseController
{
    private SettingService $settingService;

    public function __construct()
    {
        $this->settingService = new SettingService();
    }

    public function index()
    {
        $activeTab = $this->request->getGet('tab') ?? 'general';

        $data = [
            'title'      => 'Pengaturan Sistem',
            'activeTab'  => $activeTab,
            'settings'   => $this->settingService->getAll(),
        ];

        return view('pages/settings/index', $data);
    }

    public function update()
    {
        $tab = $this->request->getPost('tab') ?? 'general';

        $groupMap = [
            'general'  => 'general',
            'email'    => 'email',
            'payment'  => 'payment',
        ];

        $group = $groupMap[$tab] ?? 'general';
        $postData = $this->request->getPost();

        unset($postData['tab']);

        $this->settingService->setGroup($group, $postData);

        return redirect()->to('/settings?tab=' . $tab)->with('message', 'Pengaturan berhasil disimpan.');
    }
}
