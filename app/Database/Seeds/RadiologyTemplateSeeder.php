<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RadiologyTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'uuid'         => bin2hex(random_bytes(16)),
                'name'        => 'Rontgen Dada',
                'category'    => 'Rontgen',
                'description' => 'Pemeriksaan radiografi dada untuk evaluasi jantung, paru, dan struktur toraks.',
                'is_active'   => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'         => bin2hex(random_bytes(16)),
                'name'        => 'Rontgen Perut',
                'category'    => 'Rontgen',
                'description' => 'Pemeriksaan radiografi abdomen untuk evaluasi organ dalam dan struktur perut.',
                'is_active'   => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'         => bin2hex(random_bytes(16)),
                'name'        => 'CT Scan Kepala',
                'category'    => 'CT Scan',
                'description' => 'Pemeriksaan CT scan kepala untuk evaluasi otak, tulang tengkorak, dan struktur intrakranial.',
                'is_active'   => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'         => bin2hex(random_bytes(16)),
                'name'        => 'CT Scan Thorax',
                'category'    => 'CT Scan',
                'description' => 'Pemeriksaan CT scan dada untuk evaluasi paru, mediastinum, dan dinding dada.',
                'is_active'   => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'         => bin2hex(random_bytes(16)),
                'name'        => 'MRI Kepala',
                'category'    => 'MRI',
                'description' => 'Pemeriksaan MRI kepala untuk evaluasi detail otak, batang otak, dan struktur neurologis.',
                'is_active'   => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'         => bin2hex(random_bytes(16)),
                'name'        => 'MRI Lumbal',
                'category'    => 'MRI',
                'description' => 'Pemeriksaan MRI tulang lumbal untuk evaluasi discus intervertebralis dan kanalis spinalis.',
                'is_active'   => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'         => bin2hex(random_bytes(16)),
                'name'        => 'USG Abdomen',
                'category'    => 'USG',
                'description' => 'Pemeriksaan ultrasonografi abdomen untuk evaluasi organ dalam seperti hati, ginjal, dan limpa.',
                'is_active'   => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'         => bin2hex(random_bytes(16)),
                'name'        => 'USG Thyroid',
                'category'    => 'USG',
                'description' => 'Pemeriksaan ultrasonografi kelenjar thyroid untuk evaluasi nodul dan struktur kelenjar.',
                'is_active'   => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'         => bin2hex(random_bytes(16)),
                'name'        => 'Mamografi',
                'category'    => 'Mamografi',
                'description' => 'Pemeriksaan radiografi payudara untuk skrining dan deteksi dini kanker payudara.',
                'is_active'   => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'         => bin2hex(random_bytes(16)),
                'name'        => 'Echocardiography',
                'category'    => 'Echocardiography',
                'description' => 'Pemeriksaan ultrasonografi jantung untuk evaluasi fungsi dan struktur jantung.',
                'is_active'   => true,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('radiology_templates')->insertBatch($data);
    }
}
