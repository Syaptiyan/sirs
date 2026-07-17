<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Tunai',
                'code'       => 'CASH',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Transfer Bank',
                'code'       => 'BANK_TRANSFER',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'QRIS',
                'code'       => 'QRIS',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Kartu Debit',
                'code'       => 'DEBIT_CARD',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid'       => bin2hex(random_bytes(16)),
                'name'       => 'Kartu Kredit',
                'code'       => 'CREDIT_CARD',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('payment_methods')->insertBatch($data);
    }
}
