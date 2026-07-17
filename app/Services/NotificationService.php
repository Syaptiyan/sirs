<?php

namespace App\Services;

use App\Models\NotificationModel;

class NotificationService
{
    protected NotificationModel $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function getNotifications(int $userId, ?string $type = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->notificationModel->where('user_id', $userId);

        if ($type !== null) {
            $builder = $builder->where('type', $type);
        }

        $total = $builder->countAllResults(false);

        $notifications = $builder
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, 'default', $page);

        return [
            'data' => $notifications,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ];
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->notificationModel
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    public function markAsRead(int $notificationId): bool
    {
        return $this->notificationModel
            ->update($notificationId, ['is_read' => 1]);
    }

    public function markAllAsRead(int $userId): bool
    {
        return $this->notificationModel
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->set(['is_read' => 1])
            ->update();
    }

    public function create(int $userId, string $type, string $title, string $message, ?array $data = null): bool
    {
        $notificationData = [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data !== null ? json_encode($data) : null,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return $this->notificationModel->insert($notificationData) !== false;
    }

    public function createForRole(string $roleSlug, string $type, string $title, string $message, ?array $data = null): bool
    {
        $userModel = new \App\Models\UserModel();
        $users = $userModel->select('users.id')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->where('auth_groups.slug', $roleSlug)
            ->findAll();

        if (empty($users)) {
            return false;
        }

        $success = true;
        foreach ($users as $user) {
            if (!$this->create($user->id, $type, $title, $message, $data)) {
                $success = false;
            }
        }

        return $success;
    }
}
