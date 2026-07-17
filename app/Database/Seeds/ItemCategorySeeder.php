<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Alat Kesehatan', 'description' => 'Alat-alat medis dan kesehatan', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Alat Tulis Kantor', 'description' => 'ATK dan perlengkapan kantor', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Cleaning Supplies', 'description' => 'Alat dan bahan pembersih', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Elektronik', 'description' => 'Peralatan elektronik', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Furniture', 'description' => 'Meja, kursi, dan perabotan', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Linen', 'description' => 'Sprei, selimut, dan tekstil', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Makanan & Minuman', 'description' => 'Bahan makanan dan minuman', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Obat-obatan', 'description' => 'Farmasi dan obat', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Perlengkapan Lab', 'description' => 'Alat laboratorium dan reagen', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Sarana & Prasarana', 'description' => 'Peralatan gedung dan infrastruktur', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('item_categories')->insertBatch($data);
    }
}
