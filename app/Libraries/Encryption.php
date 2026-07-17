<?php

namespace App\Libraries;

use Config\SecurityConfig;

class Encryption
{
    private SecurityConfig $config;
    private string $key;
    private string $cipher;
    private int $keyLength;

    public function __construct()
    {
        $this->config = config('SecurityConfig');
        $this->key = $this->config->encryptionKey;
        $this->cipher = $this->config->encryptionCipher;
        $this->keyLength = $this->getKeyLength();
    }

    public function encrypt(string $data): string
    {
        if (empty($this->key)) {
            throw new \RuntimeException('Encryption key is not configured.');
        }

        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt(
            $data,
            $this->cipher,
            $this->getKey(),
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($encrypted === false) {
            throw new \RuntimeException('Encryption failed: ' . openssl_error_string());
        }

        $hmac = hash_hmac('sha256', $iv . $encrypted, $this->getKey(), true);

        return base64_encode($hmac . $iv . $encrypted);
    }

    public function decrypt(string $encryptedData): string
    {
        if (empty($this->key)) {
            throw new \RuntimeException('Encryption key is not configured.');
        }

        $decoded = base64_decode($encryptedData, true);

        if ($decoded === false) {
            throw new \InvalidArgumentException('Invalid encrypted data format.');
        }

        $ivLength = openssl_cipher_iv_length($this->cipher);
        $hmacLength = 32; // SHA-256 produces 32 bytes

        if (strlen($decoded) < $hmacLength + $ivLength) {
            throw new \InvalidArgumentException('Encrypted data is too short.');
        }

        $hmac = substr($decoded, 0, $hmacLength);
        $iv = substr($decoded, $hmacLength, $ivLength);
        $encrypted = substr($decoded, $hmacLength + $ivLength);

        $calculatedHmac = hash_hmac('sha256', $iv . $encrypted, $this->getKey(), true);

        if (!hash_equals($hmac, $calculatedHmac)) {
            throw new \RuntimeException('HMAC verification failed. Data may have been tampered with.');
        }

        $decrypted = openssl_decrypt(
            $encrypted,
            $this->cipher,
            $this->getKey(),
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($decrypted === false) {
            throw new \RuntimeException('Decryption failed: ' . openssl_error_string());
        }

        return $decrypted;
    }

    public function hashPassword(string $password): string
    {
        $hash = password_hash(
            $password,
            $this->config->hashAlgorithm,
            ['cost' => $this->config->hashCost]
        );

        if ($hash === false) {
            throw new \RuntimeException('Password hashing failed.');
        }

        return $hash;
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    public function generateUUID(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public function hashData(string $data, string $algorithm = 'sha256'): string
    {
        return hash($algorithm, $data);
    }

    public function generateHMAC(string $data): string
    {
        return hash_hmac('sha256', $data, $this->getKey());
    }

    public function verifyHMAC(string $data, string $hmac): bool
    {
        $calculatedHmac = $this->generateHMAC($data);
        return hash_equals($hmac, $calculatedHmac);
    }

    private function getKey(): string
    {
        $key = $this->key;

        if (strlen($key) < $this->keyLength) {
            $key = str_pad($key, $this->keyLength, '0');
        } elseif (strlen($key) > $this->keyLength) {
            $key = substr($key, 0, $this->keyLength);
        }

        return $key;
    }

    private function getKeyLength(): int
    {
        $lengths = [
            'aes-128-cbc' => 16,
            'aes-192-cbc' => 24,
            'aes-256-cbc' => 32,
            'aes-128-gcm' => 16,
            'aes-192-gcm' => 24,
            'aes-256-gcm' => 32,
        ];

        return $lengths[$this->cipher] ?? 32;
    }
}
