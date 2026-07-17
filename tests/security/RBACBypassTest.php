<?php

namespace Tests\Security;

use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class RBACBypassTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;

    protected $DBGroup = 'tests';
    protected $refresh = false;
    private UserModel $userModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = new UserModel();
    }

    private function loginAsUser(array $permissions = []): void
    {
        $userId = $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
            'is_active' => true,
        ]);

        session()->set([
            'logged_in' => true,
            'user_id' => $userId,
            'user_name' => 'Test User',
            'user_email' => 'test@example.com',
            'roles' => ['user'],
            'permissions' => $permissions,
            'last_activity' => time(),
        ]);
    }

    public function testUserCannotAccessAdminRoute(): void
    {
        $this->loginAsUser(['view_patients']);

        $result = $this->get('/admin/users');

        $result->assertStatus(403);
    }

    public function testUserCannotElevatePrivilegesViaRequest(): void
    {
        $this->loginAsUser(['view_patients']);

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/patients', [
            'name' => 'New Patient',
            'roles' => ['admin'],
            'permissions' => ['manage_users'],
        ]);

        $result->assertStatus(403);
    }

    public function testUserCannotAccessWithoutSpecificPermission(): void
    {
        $this->loginAsUser(['view_patients']);

        $result = $this->delete('/patients/some-uuid');

        $result->assertStatus(403);
    }

    public function testCannotBypassRBACViaDirectURLManipulation(): void
    {
        $this->loginAsUser([]);

        $result = $this->get('/settings');

        $result->assertStatus(403);
    }

    public function testCannotBypassRBACViaHTTPMethodSpoofing(): void
    {
        $this->loginAsUser(['view_patients']);

        $result = $this->withHeaders([
            'X-HTTP-Method-Override' => 'DELETE',
        ])->post('/patients/some-uuid');

        $result->assertStatus(403);
    }
}
