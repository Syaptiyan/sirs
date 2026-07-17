<?php

namespace App\Controllers;

use App\Services\NotificationService;
use CodeIgniter\API\ResponseTrait;

class NotificationController extends BaseController
{
    use ResponseTrait;

    protected NotificationService $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    public function index(): \CodeIgniter\HTTP\Response
    {
        $userId = auth()->id();
        $type = $this->request->getGet('type');
        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('per_page') ?: 20;

        $result = $this->notificationService->getNotifications($userId, $type, $page, $perPage);

        return $this->respond([
            'status' => true,
            'data' => $result['data'],
            'pagination' => $result['pagination'],
        ]);
    }

    public function markAsRead($id): \CodeIgniter\HTTP\Response
    {
        $notificationId = (int) $id;

        if ($this->notificationService->markAsRead($notificationId)) {
            return $this->respond([
                'status' => true,
                'message' => 'Notifikasi ditandai sudah dibaca',
            ]);
        }

        return $this->failNotFound('Notifikasi tidak ditemukan');
    }

    public function markAllAsRead(): \CodeIgniter\HTTP\Response
    {
        $userId = auth()->id();

        $this->notificationService->markAllAsRead($userId);

        return $this->respond([
            'status' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca',
        ]);
    }

    public function unreadCount(): \CodeIgniter\HTTP\Response
    {
        $userId = auth()->id();
        $count = $this->notificationService->getUnreadCount($userId);

        return $this->respond([
            'status' => true,
            'unread_count' => $count,
        ]);
    }
}
