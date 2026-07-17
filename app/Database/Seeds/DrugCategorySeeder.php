<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DrugCategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Analgesik', 'description' => 'Pereda nyeri dan sakit', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Antibiotik', 'description' => 'Obat anti infeksi bakteri', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Antipiretik', 'description' => 'Penurun panas/demam', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Vitamin', 'description' => 'Suplemen vitamin dan mineral', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Antihistamin', 'description' => 'Obat alergi dan gatal', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Antasida', 'description' => 'Obat lambung dan maag', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Mukolitik', 'description' => 'Obat batuk dan pengencer dahak', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Antihipertensi', 'description' => 'Obat tekanan darah tinggi', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Antidiabetes', 'description' => 'Obat diabetes/gula darah', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Kortikosteroid', 'description' => 'Obat anti inflamasi steroid', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('drug_categories')->insertBatch($data);
    }
}
