<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Users
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'users', 'name' => 'Lihat User', 'slug' => 'users.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'users', 'name' => 'Tambah User', 'slug' => 'users.create', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'users', 'name' => 'Edit User', 'slug' => 'users.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'users', 'name' => 'Hapus User', 'slug' => 'users.delete', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Roles
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'roles', 'name' => 'Lihat Role', 'slug' => 'roles.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'roles', 'name' => 'Tambah Role', 'slug' => 'roles.create', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'roles', 'name' => 'Edit Role', 'slug' => 'roles.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'roles', 'name' => 'Hapus Role', 'slug' => 'roles.delete', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Patients
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'patients', 'name' => 'Lihat Pasien', 'slug' => 'patients.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'patients', 'name' => 'Tambah Pasien', 'slug' => 'patients.create', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'patients', 'name' => 'Edit Pasien', 'slug' => 'patients.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'patients', 'name' => 'Hapus Pasien', 'slug' => 'patients.delete', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'patients', 'name' => 'Export Pasien', 'slug' => 'patients.export', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Doctors
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'doctors', 'name' => 'Lihat Dokter', 'slug' => 'doctors.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'doctors', 'name' => 'Tambah Dokter', 'slug' => 'doctors.create', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'doctors', 'name' => 'Edit Dokter', 'slug' => 'doctors.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'doctors', 'name' => 'Hapus Dokter', 'slug' => 'doctors.delete', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Medical Records
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'medical_records', 'name' => 'Lihat Rekam Medis', 'slug' => 'medical_records.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'medical_records', 'name' => 'Tambah Rekam Medis', 'slug' => 'medical_records.create', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'medical_records', 'name' => 'Edit Rekam Medis', 'slug' => 'medical_records.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'medical_records', 'name' => 'Cetak Rekam Medis', 'slug' => 'medical_records.print', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Prescriptions
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'prescriptions', 'name' => 'Lihat Resep', 'slug' => 'prescriptions.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'prescriptions', 'name' => 'Tambah Resep', 'slug' => 'prescriptions.create', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'prescriptions', 'name' => 'Edit Resep', 'slug' => 'prescriptions.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'prescriptions', 'name' => 'Dispense Resep', 'slug' => 'prescriptions.dispense', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Drugs
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'drugs', 'name' => 'Lihat Obat', 'slug' => 'drugs.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'drugs', 'name' => 'Tambah Obat', 'slug' => 'drugs.create', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'drugs', 'name' => 'Edit Obat', 'slug' => 'drugs.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'drugs', 'name' => 'Hapus Obat', 'slug' => 'drugs.delete', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Lab
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'lab', 'name' => 'Lihat Lab', 'slug' => 'lab.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'lab', 'name' => 'Tambah Lab', 'slug' => 'lab.create', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'lab', 'name' => 'Edit Lab', 'slug' => 'lab.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Radiology
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'radiology', 'name' => 'Lihat Radiologi', 'slug' => 'radiology.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'radiology', 'name' => 'Tambah Radiologi', 'slug' => 'radiology.create', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'radiology', 'name' => 'Edit Radiologi', 'slug' => 'radiology.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Inventory
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'inventory', 'name' => 'Lihat Inventori', 'slug' => 'inventory.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'inventory', 'name' => 'Tambah Inventori', 'slug' => 'inventory.create', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'inventory', 'name' => 'Edit Inventori', 'slug' => 'inventory.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Stats
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'stats', 'name' => 'Lihat Statistik', 'slug' => 'stats.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Billing
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'billing', 'name' => 'Lihat Tagihan', 'slug' => 'billing.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'billing', 'name' => 'Buat Tagihan', 'slug' => 'billing.create', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'billing', 'name' => 'Edit Tagihan', 'slug' => 'billing.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'billing', 'name' => 'Proses Pembayaran', 'slug' => 'billing.payment', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Reports
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'reports', 'name' => 'Lihat Laporan', 'slug' => 'reports.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'reports', 'name' => 'Export Laporan', 'slug' => 'reports.export', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Audit
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'audit', 'name' => 'Lihat Audit Log', 'slug' => 'audit.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],

            // Settings
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'settings', 'name' => 'Lihat Pengaturan', 'slug' => 'settings.view', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'module' => 'settings', 'name' => 'Edit Pengaturan', 'slug' => 'settings.update', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('permissions')->insertBatch($data);
    }
}
