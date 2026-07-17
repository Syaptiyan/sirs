<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Admin Sistem',
                'slug'       => 'admin',
                'is_system'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Dokter',
                'slug'       => 'doctor',
                'is_system'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Perawat',
                'slug'       => 'nurse',
                'is_system'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Kasir',
                'slug'       => 'cashier',
                'is_system'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Farmasi',
                'slug'       => 'pharmacist',
                'is_system'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Laboran',
                'slug'       => 'lab',
                'is_system'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Radiologi',
                'slug'       => 'radiology',
                'is_system'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Gudang',
                'slug'       => 'warehouse',
                'is_system'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Pasien',
                'slug'       => 'patient',
                'is_system'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Manajemen',
                'slug'       => 'management',
                'is_system'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('roles')->insertBatch($data);
    }
}
