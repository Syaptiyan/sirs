<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Gudang Utama', 'location' => 'Gedung Utama Lantai 1', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Gudang Farmasi', 'location' => 'Gedung Farmasi Lantai 1', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('warehouses')->insertBatch($data);
    }
}
