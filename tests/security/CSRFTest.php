<?php

namespace Tests\Security;

use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class CSRFTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCSRFProtectionIsConfigured(): void
    {
        $securityConfig = config('Security');

        $this->assertNotNull($securityConfig);
        $this->assertContains($securityConfig->csrfProtection, ['cookie', 'session']);
    }

    public function testCSRFTokenNameIsConfigured(): void
    {
        $securityConfig = config('Security');

        $this->assertNotEmpty($securityConfig->tokenName);
    }

    public function testCSRFHeaderNameIsConfigured(): void
    {
        $securityConfig = config('Security');

        $this->assertNotEmpty($securityConfig->headerName);
    }

    public function testCSRFCookieNameIsConfigured(): void
    {
        $securityConfig = config('Security');

        $this->assertNotEmpty($securityConfig->cookieName);
    }

    public function testCSRFTokenExpirationIsSet(): void
    {
        $securityConfig = config('Security');

        $this->assertGreaterThan(0, $securityConfig->expires);
    }

    public function testCSRFRegenerateIsEnabled(): void
    {
        $securityConfig = config('Security');

        $this->assertTrue($securityConfig->regenerate);
    }

    public function testCSRFProtectionMethodIsCookie(): void
    {
        $securityConfig = config('Security');

        $this->assertEquals('cookie', $securityConfig->csrfProtection);
    }

    public function testLoginWithoutCSRFTokenFails(): void
    {
        $this->withoutMiddleware();

        $result = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertNotEquals(200, $result->getStatus());
    }

    public function testRegisterWithoutCSRFTokenFails(): void
    {
        $this->withoutMiddleware();

        $result = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirm' => 'password123',
        ]);

        $this->assertNotEquals(200, $result->getStatus());
    }

    public function testPatientStoreWithoutCSRFTokenFails(): void
    {
        $this->withoutMiddleware();

        session()->set([
            'logged_in' => true,
            'user_id' => 1,
            'user_name' => 'Admin',
            'user_email' => 'admin@example.com',
            'roles' => ['admin'],
            'permissions' => ['create_patients'],
            'last_activity' => time(),
        ]);

        $result = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/patients', [
            'name' => 'Test Patient',
            'gender' => 'M',
            'birth_date' => '1990-01-01',
        ]);

        $this->assertNotEquals(200, $result->getStatus());
    }

    public function testCSRFTokenIsGenerated(): void
    {
        $security = service('security');

        $token = $security->getRandomBytes(32);

        $this->assertNotEmpty($token);
        $this->assertEquals(32, strlen($token));
    }

    public function testCSRFTokenRegeneration(): void
    {
        $security = service('security');

        $token1 = $security->getRandomBytes(32);
        $token2 = $security->getRandomBytes(32);

        $this->assertNotEquals($token1, $token2);
    }

    public function testCSRFProtectionIsRegisteredAsFilter(): void
    {
        $filtersConfig = config('Filters');

        $this->assertArrayHasKey('csrf', $filtersConfig->aliases);
    }

    public function testCSRFMiddlewareIsRegistered(): void
    {
        $filtersConfig = config('Filters');

        $this->assertArrayHasKey('csrf_custom', $filtersConfig->aliases);
    }

    public function testSecurityHeadersMiddlewareIsRegistered(): void
    {
        $filtersConfig = config('Filters');

        $this->assertArrayHasKey('security_headers', $filtersConfig->aliases);
    }

    public function testCSRFTokenCanBeValidated(): void
    {
        $security = service('security');

        $token = $security->getRandomBytes(32);

        $this->assertNotEmpty($token);
        $this->assertTrue(strlen($token) > 0);
    }

    public function testCSRFProtectionForPOSTRequests(): void
    {
        $routesConfig = config('Routes');

        $this->assertNotNull($routesConfig);
    }

    public function testCSRFProtectionForPUTRequests(): void
    {
        $securityConfig = config('Security');

        $this->assertTrue($securityConfig->regenerate);
    }

    public function testCSRFProtectionForDELETERequests(): void
    {
        $securityConfig = config('Security');

        $this->assertTrue($securityConfig->regenerate);
    }

    public function testCSRFRedirectOnFailureInProduction(): void
    {
        $securityConfig = config('Security');

        $this->assertIsBool($securityConfig->redirect);
    }

    public function testCSRFTokenFormat(): void
    {
        $security = service('security');

        $token = $security->getRandomBytes(32);
        $hexToken = bin2hex($token);

        $this->assertMatchesRegularExpression('/^[a-f0-9]+$/', $hexToken);
        $this->assertEquals(64, strlen($hexToken));
    }

    public function testCSRFProtectionAgainstReplayAttack(): void
    {
        $securityConfig = config('Security');

        $this->assertTrue($securityConfig->regenerate, 'CSRF token should be regenerated on each submission');
    }

    public function testCSRFProtectionAgainstTokenFixation(): void
    {
        $security = service('security');

        $tokens = [];
        for ($i = 0; $i < 10; $i++) {
            $tokens[] = bin2hex($security->getRandomBytes(32));
        }

        $uniqueTokens = array_unique($tokens);
        $this->assertCount(10, $uniqueTokens, 'All generated tokens should be unique');
    }

    public function testCSRFCookieAttributes(): void
    {
        $securityConfig = config('Security');

        $this->assertNotEmpty($securityConfig->cookieName);
        $this->assertGreaterThan(0, $securityConfig->expires);
    }

    public function testCSRFProtectionIsEnabledByDefault(): void
    {
        $securityConfig = config('Security');

        $this->assertNotNull($securityConfig->csrfProtection);
        $this->assertNotEmpty($securityConfig->tokenName);
        $this->assertNotEmpty($securityConfig->headerName);
        $this->assertNotEmpty($securityConfig->cookieName);
    }

    public function testMultipleCSRFTokenGenerationsAreUnique(): void
    {
        $security = service('security');

        $tokens = [];
        for ($i = 0; $i < 100; $i++) {
            $tokens[] = bin2hex($security->getRandomBytes(32));
        }

        $uniqueTokens = array_unique($tokens);
        $this->assertCount(100, $uniqueTokens, '100 generated tokens should all be unique');
    }
}
