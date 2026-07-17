<?php

namespace Tests\Feature;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserRoleModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class AuthControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;

    protected $DBGroup = 'tests';
    protected $refresh = false;
    private UserModel $userModel;
    private RoleModel $roleModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    public function testLoginPageRenders(): void
    {
        $result = $this->get('/login');

        $result->assertStatus(200);
    }

    public function testRegisterPageRenders(): void
    {
        $result = $this->get('/register');

        $result->assertStatus(200);
    }

    public function testLoginWithValidCredentials(): void
    {
        $password = 'password123';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $hashedPassword,
            'is_active' => true,
        ]);

        $result = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => $password,
        ]);

        $result->assertRedirectTo('/dashboard');
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $hashedPassword = password_hash('correctpassword', PASSWORD_BCRYPT, ['cost' => 12]);

        $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $hashedPassword,
            'is_active' => true,
        ]);

        $result = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
    }

    public function testLoginWithNonExistentEmail(): void
    {
        $result = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
    }

    public function testLoginValidationRequiresEmail(): void
    {
        $result = $this->post('/login', [
            'password' => 'password123',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testLoginValidationRequiresPassword(): void
    {
        $result = $this->post('/login', [
            'email' => 'test@example.com',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testLoginValidationRequiresValidEmail(): void
    {
        $result = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testRegisterSuccess(): void
    {
        $roleId = $this->roleModel->insert([
            'name' => 'Patient',
            'slug' => 'patient',
        ]);

        $result = $this->post('/register', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirm' => 'password123',
        ]);

        $result->assertRedirectTo('/login');
        $result->assertSessionHas('success');

        $user = $this->userModel->where('email', 'newuser@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('New User', $user->name);
    }

    public function testRegisterValidationRequiresName(): void
    {
        $result = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirm' => 'password123',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testRegisterValidationRequiresEmail(): void
    {
        $result = $this->post('/register', [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirm' => 'password123',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testRegisterValidationRequiresPasswordMatch(): void
    {
        $result = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirm' => 'differentpassword',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testRegisterValidationRequiresMinPasswordLength(): void
    {
        $result = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirm' => 'short',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testRegisterWithDuplicateEmail(): void
    {
        $this->userModel->insert([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        $result = $this->post('/register', [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirm' => 'password123',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testLogout(): void
    {
        $result = $this->get('/logout');

        $result->assertRedirectTo('/login');
        $result->assertSessionHas('success');
    }

    public function testForgotPasswordPageRenders(): void
    {
        $result = $this->get('/forgot-password');

        $result->assertStatus(200);
    }

    public function testForgotPasswordWithValidEmail(): void
    {
        $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
        ]);

        $result = $this->post('/forgot-password', [
            'email' => 'test@example.com',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('success');
    }

    public function testForgotPasswordWithNonExistentEmail(): void
    {
        $result = $this->post('/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
    }

    public function testForgotPasswordValidationRequiresEmail(): void
    {
        $result = $this->post('/forgot-password', []);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testResetPasswordPageRenders(): void
    {
        $result = $this->get('/reset-password/valid_token');

        $result->assertStatus(200);
    }

    public function testResetPasswordWithValidToken(): void
    {
        $this->userModel->insert([
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

        $result = $this->post('/reset-password', [
            'token' => $token,
            'password' => 'newpassword123',
            'password_confirm' => 'newpassword123',
        ]);

        $result->assertRedirectTo('/login');
        $result->assertSessionHas('success');
    }

    public function testResetPasswordWithInvalidToken(): void
    {
        $result = $this->post('/reset-password', [
            'token' => 'invalid_token',
            'password' => 'newpassword123',
            'password_confirm' => 'newpassword123',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('error');
    }

    public function testResetPasswordValidationRequiresToken(): void
    {
        $result = $this->post('/reset-password', [
            'password' => 'newpassword123',
            'password_confirm' => 'newpassword123',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testResetPasswordValidationRequiresPasswordMatch(): void
    {
        $result = $this->post('/reset-password', [
            'token' => 'some_token',
            'password' => 'newpassword123',
            'password_confirm' => 'differentpassword',
        ]);

        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }
}
