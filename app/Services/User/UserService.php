<?php

namespace App\Services\User;

use App\Models\UserModel;
use App\Models\UserRoleModel;
use App\Models\RoleModel;
use App\Models\AuditLogModel;

class UserService
{
    private UserModel $userModel;
    private UserRoleModel $userRoleModel;
    private RoleModel $roleModel;
    private AuditLogModel $auditLogModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userRoleModel = new UserRoleModel();
        $this->roleModel = new RoleModel();
        $this->auditLogModel = new AuditLogModel();
    }

    public function getAll(?string $search = null, ?int $roleId = null, ?bool $isActive = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->userModel->builder();

        if ($search !== null && $search !== '') {
            $builder = $builder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        if ($isActive !== null) {
            $builder = $builder->where('is_active', $isActive ? 1 : 0);
        }

        if ($roleId !== null) {
            $builder = $builder->join('user_roles', 'user_roles.user_id = users.id')
                ->where('user_roles.role_id', $roleId);
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $data = $builder->orderBy('created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        foreach ($data as $user) {
            $user->roles = $this->getRoles($user->id);
        }

        return [
            'data' => $data,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ];
    }

    public function getByUuid(string $uuid): ?object
    {
        $user = $this->userModel->where('uuid', $uuid)->first();

        if ($user !== null) {
            $user->roles = $this->getRoles($user->id);
        }

        return $user;
    }

    public function create(array $data): ?object
    {
        $data['uuid'] = $this->generateUuid();
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['is_active'] = $data['is_active'] ?? 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->userModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->userModel->find($id);
    }

    public function update(string $uuid, array $data): bool
    {
        $user = $this->userModel->where('uuid', $uuid)->first();

        if ($user === null) {
            return false;
        }

        if (isset($data['password']) && $data['password'] !== '') {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->userModel->update($user->id, $data);
    }

    public function delete(string $uuid): bool
    {
        $user = $this->userModel->where('uuid', $uuid)->first();

        if ($user === null) {
            return false;
        }

        return $this->userModel->delete($user->id);
    }

    public function activate(string $uuid): bool
    {
        $user = $this->userModel->where('uuid', $uuid)->first();

        if ($user === null) {
            return false;
        }

        return $this->userModel->update($user->id, [
            'is_active' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function deactivate(string $uuid): bool
    {
        $user = $this->userModel->where('uuid', $uuid)->first();

        if ($user === null) {
            return false;
        }

        return $this->userModel->update($user->id, [
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getRoles(int $userId): array
    {
        return $this->userRoleModel->select('user_roles.*, roles.name as role_name, roles.slug as role_slug')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('user_roles.user_id', $userId)
            ->where('user_roles.deleted_at', null)
            ->get()
            ->getResult();
    }

    public function assignRole(string $uuid, int $roleId): bool
    {
        $user = $this->userModel->where('uuid', $uuid)->first();

        if ($user === null) {
            return false;
        }

        $role = $this->roleModel->find($roleId);

        if ($role === null) {
            return false;
        }

        $existing = $this->userRoleModel
            ->where('user_id', $user->id)
            ->where('role_id', $roleId)
            ->where('deleted_at', null)
            ->first();

        if ($existing !== null) {
            return true;
        }

        $this->userRoleModel->insert([
            'user_id' => $user->id,
            'role_id' => $roleId,
        ]);

        return true;
    }

    public function removeRole(string $uuid, int $roleId): bool
    {
        $user = $this->userModel->where('uuid', $uuid)->first();

        if ($user === null) {
            return false;
        }

        $userRole = $this->userRoleModel
            ->where('user_id', $user->id)
            ->where('role_id', $roleId)
            ->where('deleted_at', null)
            ->first();

        if ($userRole === null) {
            return false;
        }

        return $this->userRoleModel->delete($userRole->id);
    }

    public function getActivity(string $uuid, int $page = 1, int $perPage = 20): array
    {
        $user = $this->userModel->where('uuid', $uuid)->first();

        if ($user === null) {
            return ['data' => [], 'meta' => ['total' => 0, 'page' => 1, 'per_page' => $perPage, 'total_pages' => 0]];
        }

        $total = $this->auditLogModel->where('user_id', $user->id)->countAllResults(false);
        $offset = ($page - 1) * $perPage;

        $data = $this->auditLogModel->where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'data' => $data,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ];
    }

    public function export(string $format = 'csv'): string
    {
        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();

        foreach ($users as $user) {
            $user->roles = $this->getRoles($user->id);
        }

        if ($format === 'csv') {
            return $this->exportCsv($users);
        }

        return $this->exportCsv($users);
    }

    private function exportCsv(array $users): string
    {
        $output = fopen('php://temp', 'r+');

        fputcsv($output, [
            'Nama',
            'Email',
            'Telepon',
            'Roles',
            'Status',
            'Terakhir Login',
            'Tanggal Dibuat',
        ]);

        foreach ($users as $user) {
            $roleNames = implode(', ', array_map(fn($r) => $r->role_name, $user->roles));

            fputcsv($output, [
                $user->name,
                $user->email,
                $user->phone ?? '-',
                $roleNames ?: '-',
                $user->is_active ? 'Aktif' : 'Nonaktif',
                $user->last_login_at ?? '-',
                $user->created_at,
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    private function generateUuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
