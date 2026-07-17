<?php

namespace App\Services\Role;

use App\Models\RoleModel;
use App\Models\PermissionModel;
use App\Models\RolePermissionModel;
use App\Models\UserRoleModel;

class RoleService
{
    private RoleModel $roleModel;
    private PermissionModel $permissionModel;
    private RolePermissionModel $rolePermissionModel;
    private UserRoleModel $userRoleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
        $this->rolePermissionModel = new RolePermissionModel();
        $this->userRoleModel = new UserRoleModel();
    }

    public function getAll(?string $search = null, int $page = 1, int $perPage = 20): array
    {
        $builder = $this->roleModel->builder();

        if ($search !== null && $search !== '') {
            $builder = $builder->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->groupEnd();
        }

        $total = $builder->countAllResults(false);

        $offset = ($page - 1) * $perPage;
        $data = $builder->orderBy('created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        foreach ($data as $role) {
            $role->permission_count = $this->rolePermissionModel
                ->where('role_id', $role->id)
                ->where('deleted_at', null)
                ->countAllResults(false);
            $role->user_count = $this->userRoleModel
                ->where('role_id', $role->id)
                ->where('deleted_at', null)
                ->countAllResults(false);
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
        $role = $this->roleModel->where('uuid', $uuid)->first();

        if ($role !== null) {
            $role->permissions = $this->getPermissions($role->id);
            $role->users = $this->getUsers($role->id);
        }

        return $role;
    }

    public function create(array $data): ?object
    {
        $data['uuid'] = $this->generateUuid();
        $data['slug'] = $data['slug'] ?? url_title($data['name'], '-', true);
        $data['is_active'] = $data['is_active'] ?? 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->roleModel->insert($data);

        if ($id === false) {
            return null;
        }

        return $this->roleModel->find($id);
    }

    public function update(string $uuid, array $data): bool
    {
        $role = $this->roleModel->where('uuid', $uuid)->first();

        if ($role === null) {
            return false;
        }

        if (!isset($data['slug']) || $data['slug'] === '') {
            $data['slug'] = url_title($data['name'] ?? $role->name, '-', true);
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->roleModel->update($role->id, $data);
    }

    public function delete(string $uuid): bool
    {
        $role = $this->roleModel->where('uuid', $uuid)->first();

        if ($role === null) {
            return false;
        }

        return $this->roleModel->delete($role->id);
    }

    public function getPermissions(int $roleId): array
    {
        return $this->rolePermissionModel->select('role_permissions.*, permissions.name as permission_name, permissions.slug as permission_slug, permissions.description as permission_description')
            ->join('permissions', 'permissions.id = role_permissions.permission_id')
            ->where('role_permissions.role_id', $roleId)
            ->where('role_permissions.deleted_at', null)
            ->get()
            ->getResult();
    }

    public function getUsers(int $roleId): array
    {
        return $this->userRoleModel->select('user_roles.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = user_roles.user_id')
            ->where('user_roles.role_id', $roleId)
            ->where('user_roles.deleted_at', null)
            ->get()
            ->getResult();
    }

    public function getAllPermissions(): array
    {
        return $this->permissionModel->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function getPermissionsByModule(): array
    {
        $permissions = $this->getAllPermissions();

        $grouped = [];
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->slug);
            $module = $parts[0] ?? 'general';
            $grouped[$module][] = $permission;
        }

        return $grouped;
    }

    public function updatePermissions(string $uuid, array $permissionIds): bool
    {
        $role = $this->roleModel->where('uuid', $uuid)->first();

        if ($role === null) {
            return false;
        }

        $this->rolePermissionModel->where('role_id', $role->id)->delete();

        foreach ($permissionIds as $permId) {
            $permission = $this->permissionModel->find($permId);
            if ($permission !== null) {
                $this->rolePermissionModel->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permId,
                ]);
            }
        }

        return true;
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
