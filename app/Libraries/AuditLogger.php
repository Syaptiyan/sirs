<?php

namespace App\Libraries;

use App\Services\AuditService;

/**
 * Static helper untuk mencatat audit log dengan mudah.
 */
class AuditLogger
{
    /**
     * Catat aksi CREATE.
     */
    public static function create(
        string $module,
        int $recordId,
        array $newValues,
        ?string $description = null
    ): bool {
        $service = new AuditService();

        return $service->log(
            action: 'create',
            module: $module,
            recordId: $recordId,
            oldValues: null,
            newValues: $newValues,
            description: $description
        );
    }

    /**
     * Catat aksi UPDATE.
     */
    public static function update(
        string $module,
        int $recordId,
        array $oldValues,
        array $newValues,
        ?string $description = null
    ): bool {
        $service = new AuditService();

        return $service->log(
            action: 'update',
            module: $module,
            recordId: $recordId,
            oldValues: $oldValues,
            newValues: $newValues,
            description: $description
        );
    }

    /**
     * Catat aksi DELETE.
     */
    public static function delete(
        string $module,
        int $recordId,
        array $oldValues,
        ?string $description = null
    ): bool {
        $service = new AuditService();

        return $service->log(
            action: 'delete',
            module: $module,
            recordId: $recordId,
            oldValues: $oldValues,
            newValues: null,
            description: $description
        );
    }

    /**
     * Catat aksi LOGIN.
     */
    public static function login(
        int $userId,
        string $ip,
        ?string $description = null
    ): bool {
        $service = new AuditService();

        return $service->log(
            action: 'login',
            module: 'auth',
            recordId: $userId,
            oldValues: null,
            newValues: ['ip_address' => $ip],
            description: $description ?? 'User logged in'
        );
    }

    /**
     * Catat aksi LOGOUT.
     */
    public static function logout(
        int $userId,
        string $ip,
        ?string $description = null
    ): bool {
        $service = new AuditService();

        return $service->log(
            action: 'logout',
            module: 'auth',
            recordId: $userId,
            oldValues: null,
            newValues: ['ip_address' => $ip],
            description: $description ?? 'User logged out'
        );
    }
}
