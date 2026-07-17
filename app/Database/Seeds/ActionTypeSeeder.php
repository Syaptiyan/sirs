<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ActionTypeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Konsultasi Dokter Umum', 'code' => 'KDU', 'base_price' => 150000, 'description' => 'Konsultasi dengan dokter umum', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Konsultasi Dokter Spesialis', 'code' => 'KDS', 'base_price' => 300000, 'description' => 'Konsultasi dengan dokter spesialis', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Pemeriksaan Fisik Lengkap', 'code' => 'PFL', 'base_price' => 100000, 'description' => 'Pemeriksaan fisik menyeluruh', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Pemasangan Infus', 'code' => 'INF', 'base_price' => 75000, 'description' => 'Pemasangan intravenous line', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Injeksi Intramuskular', 'code' => 'IM', 'base_price' => 50000, 'description' => 'Suntik intramuskular', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Injeksi Intravena', 'code' => 'IV', 'base_price' => 60000, 'description' => 'Suntik intravena', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Nebulizer', 'code' => 'NEB', 'base_price' => 85000, 'description' => 'Terapi nebulizer', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'EKG', 'code' => 'EKG', 'base_price' => 125000, 'description' => 'Elektrokardiografi', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Rontgen Thorax', 'code' => 'RTG', 'base_price' => 200000, 'description' => 'Foto rontgen thorax', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'USG Abdomen', 'code' => 'USG', 'base_price' => 350000, 'description' => 'Ultrasonografi abdomen', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Pemeriksaan Laboratorium Darah Lengkap', 'code' => 'DL', 'base_price' => 150000, 'description' => 'Pemeriksaan darah lengkap', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Pemeriksaan Gula Darah', 'code' => 'GD', 'base_price' => 50000, 'description' => 'Pemeriksaan glukosa darah', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Pemeriksaan Urine Lengkap', 'code' => 'PU', 'base_price' => 75000, 'description' => 'Urinalisis lengkap', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Jahitan Luka', 'code' => 'JHT', 'base_price' => 150000, 'description' => 'Penjahitan luka', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Perawatan Luka', 'code' => 'PWL', 'base_price' => 100000, 'description' => 'Perawatan dan pembersihan luka', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Tindik Vena', 'code' => 'TV', 'base_price' => 50000, 'description' => 'Pengambilan sampel darah', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Pemasangan NGT', 'code' => 'NGT', 'base_price' => 100000, 'description' => 'Pemasangan nasogastric tube', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Kateterisasi', 'code' => 'KAT', 'base_price' => 125000, 'description' => 'Pemasangan kateter urin', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Resusitasi', 'code' => 'RES', 'base_price' => 500000, 'description' => 'Tindakan resusitasi', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => service('uuid')->uuid4()->toString(), 'name' => 'Oksigen Terapi', 'code' => 'OKS', 'base_price' => 75000, 'description' => 'Pemberian terapi oksigen', 'is_active' => true, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('action_types')->insertBatch($data);
    }
}
