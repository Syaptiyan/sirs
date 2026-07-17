<?php

namespace App\Services\Auth;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserRoleModel;
use App\Models\EmailVerificationModel;
use App\Models\PasswordResetModel;
use App\Models\SessionModel;

class AuthService
{
    private UserModel $userModel;
    private RoleModel $roleModel;
    private UserRoleModel $userRoleModel;
    private EmailVerificationModel $emailVerificationModel;
    private PasswordResetModel $passwordResetModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->userRoleModel = new UserRoleModel();
        $this->emailVerificationModel = new EmailVerificationModel();
        $this->passwordResetModel = new PasswordResetModel();
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->userModel
            ->where('email', $email)
            ->where('is_active', true)
            ->first();

        if (!$user || !password_verify($password, $user->password)) {
            return null;
        }

        $this->userModel->update($user->id, [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => service('request')->getIPAddress(),
        ]);

        $roles = $this->getUserRoles($user->id);
        $permissions = $this->getUserPermissions($user->id);

        return [
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
        ];
    }

    public function register(array $data): ?object
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $data['is_active'] = true;

        $userId = $this->userModel->insert($data);

        if (!$userId) {
            return null;
        }

        $patientRole = $this->roleModel->where('slug', 'patient')->first();
        if ($patientRole) {
            $this->userRoleModel->insert([
                'user_id' => $userId,
                'role_id' => $patientRole->id,
            ]);
        }

        return $this->userModel->find($userId);
    }

    public function verifyEmail(string $token): bool
    {
        $verification = $this->emailVerificationModel
            ->where('token', $token)
            ->first();

        if (!$verification) {
            return false;
        }

        $this->userModel->update($verification->user_id, [
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        $this->emailVerificationModel->delete($verification->id);

        return true;
    }

    public function forgotPassword(string $email): ?string
    {
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return null;
        }

        $token = bin2hex(random_bytes(32));

        $this->passwordResetModel->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $token;
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        $reset = $this->passwordResetModel
            ->where('token', $token)
            ->first();

        if (!$reset) {
            return false;
        }

        $user = $this->userModel->where('email', $reset->email)->first();

        if (!$user) {
            return false;
        }

        $this->userModel->update($user->id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        $this->passwordResetModel->where('email', $reset->email)->delete();

        return true;
    }

    public function getUserRoles(int $userId): array
    {
        return $this->userRoleModel
            ->select('roles.*')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('user_roles.user_id', $userId)
            ->findAll();
    }

    public function getUserPermissions(int $userId): array
    {
        return $this->userRoleModel
            ->select('permissions.slug')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->join('role_permissions', 'role_permissions.role_id = roles.id')
            ->join('permissions', 'permissions.id = role_permissions.permission_id')
            ->where('user_roles.user_id', $userId)
            ->findAll();
    }

    public function hasPermission(int $userId, string $permission): bool
    {
        $permissions = $this->getUserPermissions($userId);
        $permissionSlugs = array_column($permissions, 'slug');

        return in_array($permission, $permissionSlugs);
    }

    public function hasRole(int $userId, string $roleSlug): bool
    {
        $roles = $this->getUserRoles($userId);
        $roleSlugs = array_column($roles, 'slug');

        return in_array($roleSlug, $roleSlugs);
    }
}
