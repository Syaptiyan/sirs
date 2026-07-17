<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class SecurityConfig extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Password Hashing Algorithm
     * --------------------------------------------------------------------------
     *
     * Algorithm used for hashing passwords.
     * PASSWORD_BCRYPT or PASSWORD_ARGON2ID
     */
    public string $hashAlgorithm = PASSWORD_BCRYPT;

    /**
     * --------------------------------------------------------------------------
     * Password Hashing Cost
     * --------------------------------------------------------------------------
     *
     * Cost factor for bcrypt hashing.
     * Higher values are more secure but slower.
     */
    public int $hashCost = 12;

    /**
     * --------------------------------------------------------------------------
     * Minimum Password Length
     * --------------------------------------------------------------------------
     */
    public int $minPasswordLength = 8;

    /**
     * --------------------------------------------------------------------------
     * Maximum Password Length
     * --------------------------------------------------------------------------
     */
    public int $maxPasswordLength = 128;

    /**
     * --------------------------------------------------------------------------
     * Require Special Characters in Password
     * --------------------------------------------------------------------------
     */
    public bool $requireSpecialChars = true;

    /**
     * --------------------------------------------------------------------------
     * Require Numbers in Password
     * --------------------------------------------------------------------------
     */
    public bool $requireNumbers = true;

    /**
     * --------------------------------------------------------------------------
     * Require Uppercase in Password
     * --------------------------------------------------------------------------
     */
    public bool $requireUppercase = true;

    /**
     * --------------------------------------------------------------------------
     * Maximum Login Attempts
     * --------------------------------------------------------------------------
     *
     * Number of failed login attempts before account lockout.
     */
    public int $maxLoginAttempts = 5;

    /**
     * --------------------------------------------------------------------------
     * Lockout Duration (seconds)
     * --------------------------------------------------------------------------
     *
     * Duration of account lockout after maximum failed attempts.
     */
    public int $lockoutDuration = 900; // 15 minutes

    /**
     * --------------------------------------------------------------------------
     * Session Timeout (seconds)
     * --------------------------------------------------------------------------
     *
     * Idle timeout for user sessions.
     */
    public int $sessionTimeout = 3600; // 1 hour

    /**
     * --------------------------------------------------------------------------
     * Remember Me Duration (seconds)
     * --------------------------------------------------------------------------
     *
     * Duration for remember me tokens.
     */
    public int $rememberMeDuration = 604800; // 7 days

    /**
     * --------------------------------------------------------------------------
     * Encryption Key
     * --------------------------------------------------------------------------
     *
     * Key used for AES-256 encryption.
     * Should be 32 bytes for AES-256.
     */
    public string $encryptionKey = '';

    /**
     * --------------------------------------------------------------------------
     * Encryption Cipher
     * --------------------------------------------------------------------------
     *
     * Cipher algorithm for encryption.
     */
    public string $encryptionCipher = 'aes-256-cbc';

    /**
     * --------------------------------------------------------------------------
     * Allowed File Types for Upload
     * --------------------------------------------------------------------------
     */
    public array $allowedFileTypes = [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
    ];

    /**
     * --------------------------------------------------------------------------
     * Maximum File Upload Size (bytes)
     * --------------------------------------------------------------------------
     */
    public int $maxFileUploadSize = 10485760; // 10MB

    /**
     * --------------------------------------------------------------------------
     * Content Security Policy
     * --------------------------------------------------------------------------
     */
    public string $contentSecurityPolicy = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self' data:;";

    /**
     * --------------------------------------------------------------------------
     * Strict Transport Security Max Age (seconds)
     * --------------------------------------------------------------------------
     */
    public int $hstsMaxAge = 31536000; // 1 year

    /**
     * --------------------------------------------------------------------------
     * Enable HSTS Include Subdomains
     * --------------------------------------------------------------------------
     */
    public bool $hstsIncludeSubdomains = true;

    /**
     * --------------------------------------------------------------------------
     * Enable HSTS Preload
     * --------------------------------------------------------------------------
     */
    public bool $hstsPreload = true;

    /**
     * --------------------------------------------------------------------------
     * X-Frame-Options
     * --------------------------------------------------------------------------
     */
    public string $xFrameOptions = 'SAMEORIGIN';

    /**
     * --------------------------------------------------------------------------
     * X-Content-Type-Options
     * --------------------------------------------------------------------------
     */
    public string $xContentTypeOptions = 'nosniff';

    /**
     * --------------------------------------------------------------------------
     * X-XSS-Protection
     * --------------------------------------------------------------------------
     */
    public string $xXssProtection = '1; mode=block';

    /**
     * --------------------------------------------------------------------------
     * Referrer Policy
     * --------------------------------------------------------------------------
     */
    public string $referrerPolicy = 'strict-origin-when-cross-origin';

    /**
     * --------------------------------------------------------------------------
     * Permissions Policy
     * --------------------------------------------------------------------------
     */
    public string $permissionsPolicy = 'camera=(), microphone=(), geolocation=()';

    /**
     * --------------------------------------------------------------------------
     * API Rate Limit (requests per minute)
     * --------------------------------------------------------------------------
     */
    public int $apiRateLimit = 60;

    /**
     * --------------------------------------------------------------------------
     * Enable Audit Logging
     * --------------------------------------------------------------------------
     */
    public bool $auditLogging = true;

    /**
     * --------------------------------------------------------------------------
     * Sensitive Fields to Exclude from Logs
     * --------------------------------------------------------------------------
     */
    public array $sensitiveFields = [
        'password',
        'password_confirm',
        'token',
        'credit_card',
        'cvv',
        'ssn',
    ];
}
