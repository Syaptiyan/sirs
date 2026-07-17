<?php

namespace Tests\Unit\Models;

use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class UserModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    private UserModel $userModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = new UserModel();
    }

    public function testInsertUserSuccess(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ];

        $id = $this->userModel->insert($data);

        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);
    }

    public function testInsertGeneratesUUID(): void
    {
        $id = $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $user = $this->userModel->find($id);

        $this->assertNotNull($user->uuid);
        $this->assertNotEmpty($user->uuid);
    }

    public function testValidationRequiresName(): void
    {
        $this->userModel->insert([
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $this->assertFalse($this->userModel->skipValidation(true)->insert([
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]));
    }

    public function testValidationRequiresValidEmail(): void
    {
        $result = $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $this->assertFalse($result);
    }

    public function testValidationRequiresMinPasswordLength(): void
    {
        $result = $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('short', PASSWORD_BCRYPT),
        ]);

        $this->assertFalse($result);
    }

    public function testFindUserById(): void
    {
        $id = $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $user = $this->userModel->find($id);

        $this->assertIsObject($user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
    }

    public function testFindUserByEmail(): void
    {
        $this->userModel->insert([
            'name' => 'Test User',
            'email' => 'findme@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $user = $this->userModel->where('email', 'findme@example.com')->first();

        $this->assertIsObject($user);
        $this->assertEquals('findme@example.com', $user->email);
    }

    public function testUpdateUser(): void
    {
        $id = $this->userModel->insert([
            'name' => 'Old Name',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $this->userModel->update($id, ['name' => 'New Name']);

        $user = $this->userModel->find($id);
        $this->assertEquals('New Name', $user->name);
    }

    public function testSoftDelete(): void
    {
        $id = $this->userModel->insert([
            'name' => 'To Delete',
            'email' => 'delete@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $this->userModel->delete($id);

        $user = $this->userModel->find($id);
        $this->assertNull($user);

        $userWithTrashed = $this->userModel->withDeleted()->find($id);
        $this->assertNotNull($userWithTrashed);
        $this->assertNotNull($userWithTrashed->deleted_at);
    }

    public function testTimestampsAreSet(): void
    {
        $id = $this->userModel->insert([
            'name' => 'Timestamp User',
            'email' => 'timestamp@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $user = $this->userModel->find($id);

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }

    public function testAllowedFields(): void
    {
        $allowedFields = $this->userModel->allowedFields;

        $this->assertContains('name', $allowedFields);
        $this->assertContains('email', $allowedFields);
        $this->assertContains('password', $allowedFields);
        $this->assertContains('phone', $allowedFields);
        $this->assertContains('is_active', $allowedFields);
        $this->assertContains('uuid', $allowedFields);
    }

    public function testReturnTypeIsObject(): void
    {
        $this->assertEquals('object', $this->userModel->returnType);
    }

    public function testTableName(): void
    {
        $this->assertEquals('users', $this->userModel->table);
    }

    public function testSoftDeletesEnabled(): void
    {
        $this->assertTrue($this->userModel->useSoftDeletes);
    }

    public function testFindAllUsers(): void
    {
        $this->userModel->insert([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $this->userModel->insert([
            'name' => 'User 2',
            'email' => 'user2@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
        ]);

        $users = $this->userModel->findAll();

        $this->assertGreaterThanOrEqual(2, count($users));
    }

    public function testWhereClauseFiltering(): void
    {
        $this->userModel->insert([
            'name' => 'Active User',
            'email' => 'active@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'is_active' => true,
        ]);

        $this->userModel->insert([
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'is_active' => false,
        ]);

        $activeUsers = $this->userModel->where('is_active', true)->findAll();

        foreach ($activeUsers as $user) {
            $this->assertTrue((bool) $user->is_active);
        }
    }
}
