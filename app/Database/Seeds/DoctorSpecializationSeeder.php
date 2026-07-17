<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DoctorSpecializationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Umum', 'description' => 'Dokter Umum', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Anak', 'description' => 'Spesialis Anak', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Bedah', 'description' => 'Spesialis Bedah', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Jantung', 'description' => 'Spesialis Jantung dan Pembuluh Darah', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Saraf', 'description' => 'Spesialis Saraf', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Kulit', 'description' => 'Spesialis Kulit dan Kelamin', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Mata', 'description' => 'Spesialis Mata', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'THT', 'description' => 'Spesialis Telinga, Hidung, dan Tenggorokan', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Gigi', 'description' => 'Spesialis Gigi dan Mulut', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Kandungan', 'description' => 'Spesialis Obstetri dan Ginekologi', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Paru', 'description' => 'Spesialis Paru', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Ortopedi', 'description' => 'Spesialis Bedah Ortopedi', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Psikiatri', 'description' => 'Spesialis Kedokteran Jiwa', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Urologi', 'description' => 'Spesialis Urologi', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['uuid' => bin2hex(random_bytes(16)), 'name' => 'Gizi', 'description' => 'Spesialis Gizi Klinik', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('doctor_specializations')->insertBatch($data);
    }
}
