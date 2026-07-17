<?php

namespace Tests\Unit\Services;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserRoleModel;
use App\Services\Auth\AuthService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class AuthServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    private AuthService $authService;
    private UserModel $userModel;
    private RoleModel $roleModel;
    private UserRoleModel $userRoleModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->userRoleModel = new UserRoleModel();
    }

    public function testLoginWithValidCredentials(): void
    {
        $password = 'password123';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $userId = $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $hashedPassword,
            'is_active' => true,
        ]);

        $result = $this->authService->login('test@example.com', $password);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('roles', $result);
        $this->assertArrayHasKey('permissions', $result);
        $this->assertEquals($userId, $result['user']->id);
        $this->assertEquals('Test User', $result['user']->name);
    }

    public function testLoginWithInvalidPassword(): void
    {
        $hashedPassword = password_hash('correctpassword', PASSWORD_BCRYPT, ['cost' => 12]);

        $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $hashedPassword,
            'is_active' => true,
        ]);

        $result = $this->authService->login('test@example.com', 'wrongpassword');

        $this->assertNull($result);
    }

    public function testLoginWithNonExistentEmail(): void
    {
        $result = $this->authService->login('nonexistent@example.com', 'password123');

        $this->assertNull($result);
    }

    public function testLoginWithInactiveUser(): void
    {
        $password = 'password123';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $this->userModel->insert([
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'password' => $hashedPassword,
            'is_active' => false,
        ]);

        $result = $this->authService->login('inactive@example.com', $password);

        $this->assertNull($result);
    }

    public function testRegisterSuccess(): void
    {
        $roleId = $this->roleModel->insert([
            'name' => 'Patient',
            'slug' => 'patient',
        ]);

        $user = $this->authService->register([
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
        ]);

        $this->assertIsObject($user);
        $this->assertEquals('New User', $user->name);
        $this->assertEquals('newuser@example.com', $user->email);
        $this->assertTrue(password_verify('password123', $user->password));
        $this->assertTrue($user->is_active);
    }

    public function testRegisterWithDuplicateEmail(): void
    {
        $this->userModel->insert([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        $result = $this->authService->register([
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
        ]);

        $this->assertNull($result);
    }

    public function testVerifyEmailSuccess(): void
    {
        $userId = $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        $token = bin2hex(random_bytes(32));
        $verificationModel = new \App\Models\EmailVerificationModel();
        $verificationModel->insert([
            'user_id' => $userId,
            'token' => $token,
        ]);

        $result = $this->authService->verifyEmail($token);

        $this->assertTrue($result);

        $user = $this->userModel->find($userId);
        $this->assertNotNull($user->email_verified_at);
    }

    public function testVerifyEmailWithInvalidToken(): void
    {
        $result = $this->authService->verifyEmail('invalid_token');

        $this->assertFalse($result);
    }

    public function testForgotPasswordSuccess(): void
    {
        $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        $token = $this->authService->forgotPassword('test@example.com');

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }

    public function testForgotPasswordWithNonExistentEmail(): void
    {
        $token = $this->authService->forgotPassword('nonexistent@example.com');

        $this->assertNull($token);
    }

    public function testResetPasswordSuccess(): void
    {
        $userId = $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('oldpassword', PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        $token = 'valid_reset_token';
        $passwordResetModel = new \App\Models\PasswordResetModel();
        $passwordResetModel->insert([
            'email' => 'test@example.com',
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $result = $this->authService->resetPassword($token, 'newpassword123');

        $this->assertTrue($result);

        $user = $this->userModel->find($userId);
        $this->assertTrue(password_verify('newpassword123', $user->password));
    }

    public function testResetPasswordWithInvalidToken(): void
    {
        $result = $this->authService->resetPassword('invalid_token', 'newpassword123');

        $this->assertFalse($result);
    }

    public function testHasPermission(): void
    {
        $userId = $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        $roleId = $this->roleModel->insert([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        $this->userRoleModel->insert([
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);

        $permissionModel = new \App\Models\PermissionModel();
        $permissionId = $permissionModel->insert([
            'name' => 'View Users',
            'slug' => 'view_users',
        ]);

        $rolePermissionModel = new \App\Models\RolePermissionModel();
        $rolePermissionModel->insert([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
        ]);

        $this->assertTrue($this->authService->hasPermission($userId, 'view_users'));
        $this->assertFalse($this->authService->hasPermission($userId, 'delete_users'));
    }

    public function testHasRole(): void
    {
        $userId = $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        $roleId = $this->roleModel->insert([
            'name' => 'Doctor',
            'slug' => 'doctor',
        ]);

        $this->userRoleModel->insert([
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);

        $this->assertTrue($this->authService->hasRole($userId, 'doctor'));
        $this->assertFalse($this->authService->hasRole($userId, 'admin'));
    }
}
