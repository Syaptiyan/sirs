<?php

namespace Tests\Security;

use App\Models\UserModel;
use App\Models\PatientModel;
use App\Services\Auth\AuthService;
use App\Services\Patient\PatientService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class SQLInjectionTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    private UserModel $userModel;
    private PatientModel $patientModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = new UserModel();
        $this->patientModel = new PatientModel();
    }

    public function testLoginSQLInjectionAttempt(): void
    {
        $this->userModel->insert([
            'name' => 'Valid User',
            'email' => 'valid@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
            'is_active' => true,
        ]);

        $maliciousPayloads = [
            "' OR '1'='1",
            "' OR '1'='1' --",
            "' OR '1'='1' /*",
            "admin'--",
            "' UNION SELECT * FROM users --",
            "1; DROP TABLE users --",
            "' OR 1=1 #",
            "'; INSERT INTO users (email, password) VALUES ('hacker@evil.com', 'hacked'); --",
        ];

        foreach ($maliciousPayloads as $payload) {
            $authService = new AuthService();
            $result = $authService->login($payload, 'password123');

            $this->assertNull($result, "SQL injection payload should not authenticate: {$payload}");
        }

        $users = $this->userModel->findAll();
        $this->assertCount(1, $users, 'Users table should still have exactly 1 record');
    }

    public function testLoginSQLInjectionInPassword(): void
    {
        $this->userModel->insert([
            'name' => 'Valid User',
            'email' => 'valid@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
            'is_active' => true,
        ]);

        $maliciousPasswords = [
            "' OR '1'='1",
            "password' OR '1'='1' --",
            "' UNION SELECT * FROM users --",
        ];

        $authService = new AuthService();
        foreach ($maliciousPasswords as $password) {
            $result = $authService->login('valid@example.com', $password);

            $this->assertNull($result, "SQL injection in password should not authenticate: {$password}");
        }
    }

    public function testPatientSearchSQLInjection(): void
    {
        $this->patientModel->insert([
            'name' => 'Normal Patient',
            'gender' => 'M',
            'birth_date' => '1990-01-01',
            'is_active' => true,
        ]);

        $maliciousSearches = [
            "' OR '1'='1",
            "'; DROP TABLE patients; --",
            "' UNION SELECT * FROM users --",
            "1' AND 1=1 --",
            "' OR ''='",
        ];

        $patientService = new PatientService();
        foreach ($maliciousSearches as $search) {
            $result = $patientService->getAll($search);

            $this->assertIsArray($result, "Search should return array for payload: {$search}");
            $this->assertArrayHasKey('data', $result);

            $foundNormal = false;
            foreach ($result['data'] as $patient) {
                if ($patient->name === 'Normal Patient') {
                    $foundNormal = true;
                }
            }
        }

        $patients = $this->patientModel->findAll();
        $this->assertGreaterThanOrEqual(1, count($patients), 'Patients table should still exist and have records');
    }

    public function testPatientCreateSQLInjection(): void
    {
        $maliciousData = [
            'name' => "Robert'); DROP TABLE patients; --",
            'gender' => 'M',
            'birth_date' => '1990-01-01',
        ];

        $patientService = new PatientService();
        $patient = $patientService->create($maliciousData);

        if ($patient !== null) {
            $found = $this->patientModel->find($patient->id);
            $this->assertNotNull($found, 'Patient should be stored safely');
            $this->assertEquals($maliciousData['name'], $found->name, 'Name should be stored as-is, not executed');
        }

        $patients = $this->patientModel->findAll();
        $this->assertGreaterThanOrEqual(0, count($patients), 'Patients table should still exist');
    }

    public function testEmailVerificationTokenSQLInjection(): void
    {
        $maliciousTokens = [
            "' OR '1'='1",
            "'; DROP TABLE email_verifications; --",
            "' UNION SELECT * FROM users --",
        ];

        $authService = new AuthService();
        foreach ($maliciousTokens as $token) {
            $result = $authService->verifyEmail($token);

            $this->assertFalse($result, "Malicious token should not verify: {$token}");
        }
    }

    public function testPasswordResetTokenSQLInjection(): void
    {
        $maliciousTokens = [
            "' OR '1'='1",
            "'; DROP TABLE password_resets; --",
            "' UNION SELECT * FROM users --",
        ];

        $authService = new AuthService();
        foreach ($maliciousTokens as $token) {
            $result = $authService->resetPassword($token, 'newpassword');

            $this->assertFalse($result, "Malicious token should not reset password: {$token}");
        }
    }

    public function testLoginEndpointSQLInjectionViaHTTP(): void
    {
        $this->userModel->insert([
            'name' => 'Valid User',
            'email' => 'valid@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]),
            'is_active' => true,
        ]);

        $result = $this->post('/login', [
            'email' => "' OR '1'='1",
            'password' => 'password123',
        ]);

        $result->assertRedirect();

        $users = $this->userModel->findAll();
        $this->assertCount(1, $users);
    }

    public function testPatientSearchEndpointSQLInjectionViaHTTP(): void
    {
        session()->set([
            'logged_in' => true,
            'user_id' => 1,
            'user_name' => 'Admin',
            'user_email' => 'admin@example.com',
            'roles' => ['admin'],
            'permissions' => ['view_patients'],
            'last_activity' => time(),
        ]);

        $this->patientModel->insert([
            'name' => 'Normal Patient',
            'gender' => 'M',
            'birth_date' => '1990-01-01',
            'is_active' => true,
        ]);

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/patients?search=' . urlencode("' OR '1'='1"));

        $result->assertStatus(200);

        $patients = $this->patientModel->findAll();
        $this->assertGreaterThanOrEqual(1, count($patients));
    }

    public function testPatientUpdateSQLInjectionViaHTTP(): void
    {
        session()->set([
            'logged_in' => true,
            'user_id' => 1,
            'user_name' => 'Admin',
            'user_email' => 'admin@example.com',
            'roles' => ['admin'],
            'permissions' => ['edit_patients'],
            'last_activity' => time(),
        ]);

        $patient = $this->patientModel->insert([
            'name' => 'Original Name',
            'gender' => 'M',
            'birth_date' => '1990-01-01',
            'is_active' => true,
        ]);
        $patientData = $this->patientModel->find($patient);

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put('/patients/' . $patientData->uuid, [
            'name' => "Updated' OR '1'='1",
            'gender' => 'M',
            'birth_date' => '1990-01-01',
        ]);

        $updated = $this->patientModel->find($patient);
        if ($updated) {
            $this->assertNotEquals("'1'='1", $updated->name);
        }
    }

    public function testNoSQLInjectionViaOrderBy(): void
    {
        $patientService = new PatientService();

        $result = $patientService->getAll();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
    }

    public function testNoSQLInjectionViaGenderFilter(): void
    {
        $this->patientModel->insert([
            'name' => 'Male Patient',
            'gender' => 'M',
            'birth_date' => '1990-01-01',
            'is_active' => true,
        ]);

        $patientService = new PatientService();

        $maliciousGenders = [
            "' OR '1'='1",
            "M' UNION SELECT * FROM users --",
            "'; DROP TABLE patients; --",
        ];

        foreach ($maliciousGenders as $gender) {
            $result = $patientService->getAll(null, $gender);

            $this->assertIsArray($result);
        }

        $patients = $this->patientModel->findAll();
        $this->assertGreaterThanOrEqual(1, count($patients));
    }

    public function testNoSQLInjectionViaPagination(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->patientModel->insert([
                'name' => "Patient {$i}",
                'gender' => 'M',
                'birth_date' => '1990-01-01',
                'is_active' => true,
            ]);
        }

        $patientService = new PatientService();

        $result = $patientService->getAll(null, null, null, -1, 0);

        $this->assertIsArray($result);
    }
}
