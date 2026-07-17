<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LabTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Darah Lengkap',
                'category'   => 'Hematologi',
                'items'      => json_encode([
                    'Hemoglobin (Hb)', 'Hematokrit (Ht)', 'Eritrosit', 'Leukosit',
                    'Trombosit', 'LED', 'Hitung Jenis Leukosit', 'MCV', 'MCH', 'MCHC',
                ]),
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Urine Lengkap',
                'category'   => 'Kimia Klinik',
                'items'      => json_encode([
                    'Warna', 'Kejernihan', 'pH', 'Berat Jenis', 'Protein', 'Glukosa',
                    'Bilirubin', 'Urobilinogen', 'Keton', 'Nitrit', 'Leukosit Urine', 'Eritrosit Urine',
                ]),
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Gula Darah',
                'category'   => 'Kimia Klinik',
                'items'      => json_encode([
                    'Glukosa Puasa', 'Glukosa 2 Jam PP', 'HbA1c',
                ]),
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Kolesterol',
                'category'   => 'Kimia Klinik',
                'items'      => json_encode([
                    'Kolesterol Total', 'Trigliserida', 'HDL', 'LDL',
                ]),
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'SGOT',
                'category'   => 'Fungsi Hati',
                'items'      => json_encode([
                    'SGOT (AST)',
                ]),
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'SGPT',
                'category'   => 'Fungsi Hati',
                'items'      => json_encode([
                    'SGPT (ALT)',
                ]),
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Kreatinin',
                'category'   => 'Fungsi Ginjal',
                'items'      => json_encode([
                    'Kreatinin', 'BUN (Ureum)', 'Asam Urat',
                ]),
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Asam Urat',
                'category'   => 'Kimia Klinik',
                'items'      => json_encode([
                    'Asam Urat',
                ]),
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'HBsAg',
                'category'   => 'Serologi',
                'items'      => json_encode([
                    'HBsAg (Qualitative)',
                ]),
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Anti-HCV',
                'category'   => 'Serologi',
                'items'      => json_encode([
                    'Anti-HCV (Qualitative)',
                ]),
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('lab_templates')->insertBatch($data);
    }
}
