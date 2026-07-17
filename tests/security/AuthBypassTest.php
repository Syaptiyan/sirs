<?php

namespace Tests\Security;

use App\Models\UserModel;
use App\Services\Auth\AuthService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class AuthBypassTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    private UserModel $userModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = new UserModel();
    }

    public function testCannotAccessProtectedRouteWithoutLogin(): void
    {
        $result = $this->get('/dashboard');

        $result->assertRedirect();
    }

    public function testCannotBypassAuthWithFakeSession(): void
    {
        session()->set([
            'logged_in' => true,
            'user_id' => 99999,
        ]);

        $result = $this->get('/dashboard');

        $result->assertRedirect();
    }

    public function testCannotBypassAuthWithManipulatedCookie(): void
    {
        $result = $this->withHeaders([
            'Cookie' => 'logged_in=true; user_id=1',
        ])->get('/dashboard');

        $result->assertRedirect();
    }

    public function testInactiveUserCannotLogin(): void
    {
        $this->userModel->insert([
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
            'is_active' => false,
        ]);

        $authService = new AuthService();
        $result = $authService->login('inactive@example.com', 'password123');

        $this->assertNull($result);
    }

    public function testCannotReuseExpiredToken(): void
    {
        $authService = new AuthService();

        $result = $authService->verifyEmail('expired-token-12345');

        $this->assertFalse($result);
    }
}
