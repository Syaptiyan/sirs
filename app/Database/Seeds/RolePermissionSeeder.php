<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles       = $this->db->table('roles')->get()->getResultArray();
        $permissions = $this->db->table('permissions')->get()->getResultArray();

        $roleMap       = array_column($roles, 'id', 'slug');
        $permissionMap = array_column($permissions, 'id', 'slug');

        $assignments = [
            // Admin: semua permissions
            'admin' => array_values($permissionMap),

            // Dokter
            'doctor' => [
                'patients.view',
                'medical_records.view',
                'medical_records.create',
                'medical_records.update',
                'medical_records.print',
                'prescriptions.view',
                'prescriptions.create',
                'prescriptions.update',
            ],

            // Perawat
            'nurse' => [
                'patients.view',
                'medical_records.view',
                'prescriptions.view',
            ],

            // Kasir
            'cashier' => [
                'billing.view',
                'billing.create',
                'billing.update',
                'billing.payment',
            ],

            // Farmasi
            'pharmacist' => [
                'prescriptions.view',
                'prescriptions.dispense',
                'drugs.view',
                'drugs.create',
                'drugs.update',
            ],

            // Lab
            'lab' => [
                'lab.view',
                'lab.create',
                'lab.update',
            ],

            // Radiology
            'radiology' => [
                'radiology.view',
                'radiology.create',
                'radiology.update',
            ],

            // Warehouse
            'warehouse' => [
                'inventory.view',
                'inventory.create',
                'inventory.update',
            ],

            // Management
            'management' => [
                'reports.view',
                'reports.export',
                'stats.view',
            ],

            // Patient
            'patient' => [
                'patients.view',
            ],
        ];

        $data = [];
        foreach ($assignments as $roleSlug => $permSlugs) {
            $roleId = $roleMap[$roleSlug] ?? null;
            if (!$roleId) {
                continue;
            }

            foreach ($permSlugs as $permSlug) {
                $permId = $permissionMap[$permSlug] ?? null;
                if (!$permId) {
                    continue;
                }

                $data[] = [
                    'role_id'       => $roleId,
                    'permission_id' => $permId,
                    'created_at'    => date('Y-m-d H:i:s'),
                ];
            }
        }

        if (!empty($data)) {
            $this->db->table('role_permissions')->insertBatch($data);
        }
    }
}
