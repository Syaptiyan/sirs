# 11 - TESTING

## Testing Strategy

### Test Pyramid

```
         /\
        /  \        E2E (10%)
       /    \
      /------\      Integration (20%)
     /        \
    /----------\    Unit (70%)
```

### Test Types

| Type | Coverage Target | Tools |
|------|----------------|-------|
| Unit Test | 80%+ | PHPUnit |
| Integration Test | Critical paths | PHPUnit + Database |
| Feature Test | All endpoints | PHPUnit + HTTP |
| Security Test | OWASP Top 10 | Manual + Automated |

---

## PHPUnit Configuration

### phpunit.xml.dist

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/unit</directory>
        </testsuite>
        <testsuite name="Integration">
            <directory>tests/integration</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/feature</directory>
        </testsuite>
    </testsuites>
    <coverage>
        <include>
            <directory suffix=".php">app</directory>
        </include>
        <exclude>
            <directory>app/Config</directory>
            <directory>app/Views</directory>
        </exclude>
    </coverage>
</phpunit>
```

---

## Unit Tests

### Contoh: Service Test

```php
<?php

namespace Tests\Unit\Services;

use App\Services\Auth\AuthService;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;

class AuthServiceTest extends CIUnitTestCase
{
    private AuthService $authService;
    private UserModel $userModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userModel = new UserModel();
        $this->authService = new AuthService($this->userModel);
    }

    public function testLoginWithValidCredentials(): void
    {
        $user = $this->createTestUser();
        $result = $this->authService->login($user['email'], 'password123');

        $this->assertNotNull($result);
        $this->assertEquals($user['email'], $result['user']['email']);
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $result = $this->authService->login('test@example.com', 'wrongpassword');

        $this->assertNull($result);
    }

    public function testLoginWithInactiveUser(): void
    {
        $user = $this->createTestUser(['is_active' => false]);
        $result = $this->authService->login($user['email'], 'password123');

        $this->assertNull($result);
    }

    private function createTestUser(array $overrides = []): array
    {
        $data = array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'is_active' => true,
        ], $overrides);

        $this->userModel->insert($data);
        return $data;
    }
}
```

### Contoh: Model Test

```php
<?php

namespace Tests\Unit\Models;

use App\Models\PatientModel;
use CodeIgniter\Test\CIUnitTestCase;

class PatientModelTest extends CIUnitTestCase
{
    private PatientModel $patientModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->patientModel = new PatientModel();
    }

    public function testCreatePatient(): void
    {
        $data = [
            'name' => 'John Doe',
            'mrn' => 'RM-20240101-0001',
            'birth_date' => '1990-01-01',
            'gender' => 'L',
        ];

        $id = $this->patientModel->insert($data);
        $patient = $this->patientModel->find($id);

        $this->assertEquals('John Doe', $patient['name']);
        $this->assertEquals('RM-20240101-0001', $patient['mrn']);
    }

    public function testGenerateMRN(): void
    {
        $mrn = $this->patientModel->generateMRN();

        $this->assertMatchesRegularExpression('/^RM-\d{8}-\d{4}$/', $mrn);
    }
}
```

---

## Feature Tests

### Contoh: API Endpoint Test

```php
<?php

namespace Tests\Feature\Api;

use CodeIgniter\Test\FeatureTestCase;
use App\Models\UserModel;

class PatientApiTest extends FeatureTestCase
{
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = $this->getAuthToken();
    }

    public function testGetPatientsList(): void
    {
        $result = $this->get('/api/v1/patients', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $result->assertStatus(200);
        $result->assertJSONStructure([
            'success',
            'message',
            'data' => [
                '*' => ['uuid', 'mrn', 'name', 'gender'],
            ],
            'meta' => ['page', 'per_page', 'total'],
        ]);
    }

    public function testCreatePatient(): void
    {
        $data = [
            'name' => 'Jane Doe',
            'birth_date' => '1995-05-15',
            'gender' => 'P',
            'phone' => '08123456789',
        ];

        $result = $this->post('/api/v1/patients', $data, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $result->assertStatus(201);
        $result->assertJSONFragment(['name' => 'Jane Doe']);
    }

    public function testCreatePatientValidation(): void
    {
        $data = [
            'name' => '',
            'birth_date' => 'invalid',
        ];

        $result = $this->post('/api/v1/patients', $data, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $result->assertStatus(422);
        $result->assertJSONStructure(['errors']);
    }

    public function testUnauthorizedAccess(): void
    {
        $result = $this->get('/api/v1/patients');

        $result->assertStatus(401);
    }

    private function getAuthToken(): string
    {
        $result = $this->post('/api/v1/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        return $result->getJSON()['data']['token'];
    }
}
```

---

## Security Tests

### Checklist

| Test | Description |
|------|-------------|
| SQL Injection | Test semua input field |
| XSS | Test reflected dan stored XSS |
| CSRF | Test form tanpa CSRF token |
| Authentication | Test akses tanpa token |
| Authorization | Test akses role berbeda |
| Rate Limiting | Test exceed rate limit |
| Session | Test session fixation/hijacking |
| File Upload | Test malicious file upload |

### Contoh: Security Test

```php
<?php

namespace Tests\Feature\Security;

use CodeIgniter\Test\FeatureTestCase;

class SecurityTest extends FeatureTestCase
{
    public function testSQLInjectionPrevention(): void
    {
        $maliciousInput = "'; DROP TABLE users; --";

        $result = $this->get('/api/v1/patients?search=' . urlencode($maliciousInput), [
            'Authorization' => 'Bearer ' . $this->getAuthToken(),
        ]);

        $result->assertStatus(200);
        // Table should still exist
        $this->assertTrue($this->db->tableExists('users'));
    }

    public function testXSSPrevention(): void
    {
        $xssPayload = '<script>alert("xss")</script>';

        $result = $this->post('/api/v1/patients', [
            'name' => $xssPayload,
            'birth_date' => '1990-01-01',
            'gender' => 'L',
        ], [
            'Authorization' => 'Bearer ' . $this->getAuthToken(),
        ]);

        $result->assertStatus(201);
        $result->assertJSONFragment([
            'name' => htmlspecialchars($xssPayload),
        ]);
    }

    public function testCSRFProtection(): void
    {
        $result = $this->post('/api/v1/patients', [
            'name' => 'Test',
        ]);

        $result->assertStatus(403);
    }

    public function testUnauthorizedAccess(): void
    {
        $result = $this->get('/api/v1/patients');

        $result->assertStatus(401);
    }

    public function testRoleBasedAccess(): void
    {
        // Doctor should not access admin endpoints
        $token = $this->getDoctorToken();

        $result = $this->get('/api/v1/users', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $result->assertStatus(403);
    }
}
```

---

## Database Testing

### Migration Testing

```php
<?php

namespace Tests\Unit\Database;

use CodeIgniter\Test\CIUnitTestCase;

class MigrationTest extends CIUnitTestCase
{
    public function testAllTablesExist(): void
    {
        $tables = [
            'users', 'roles', 'permissions', 'role_permissions',
            'user_roles', 'sessions', 'patients', 'doctors',
            'nurses', 'polyclinics', 'rooms', 'visits',
            'medical_records', 'prescriptions', 'drugs',
            'invoices', 'payments',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(
                $this->db->tableExists($table),
                "Table {$table} should exist"
            );
        }
    }
}
```

---

## Running Tests

### Commands

```bash
# Run all tests
php vendor/bin/phpunit

# Run specific test suite
php vendor/bin/phpunit --testsuite Unit
php vendor/bin/phpunit --testsuite Feature

# Run with coverage
php vendor/bin/phpunit --coverage-html tests/coverage

# Run specific test file
php vendor/bin/phpunit tests/unit/Services/AuthServiceTest.php

# Run specific test method
php vendor/bin/phpunit --filter testLoginWithValidCredentials
```

### CI/CD Integration

```yaml
# .github/workflows/test.yml
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
      - run: composer install
      - run: php vendor/bin/phpunit --coverage-clover coverage.xml
```

---

## Test Data Management

### Factories

```php
<?php

namespace Tests\Support\Factories;

use Faker\Factory;

class PatientFactory
{
    public static function create(array $overrides = []): array
    {
        $faker = Factory::create();

        return array_merge([
            'name' => $faker->name,
            'mrn' => 'RM-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'birth_date' => $faker->date('Y-m-d', '-20 years'),
            'gender' => $faker->randomElement(['L', 'P']),
            'phone' => $faker->phoneNumber,
            'email' => $faker->email,
            'address' => $faker->address,
        ], $overrides);
    }
}
```

### Seeders

```php
<?php

namespace Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $this->db->table('users')->insert([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'is_active' => true,
        ]);

        // Create test patients
        for ($i = 1; $i <= 10; $i++) {
            $this->db->table('patients')->insert([
                'name' => "Patient {$i}",
                'mrn' => 'RM-' . date('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'birth_date' => '1990-01-01',
                'gender' => $i % 2 === 0 ? 'L' : 'P',
            ]);
        }
    }
}
```
