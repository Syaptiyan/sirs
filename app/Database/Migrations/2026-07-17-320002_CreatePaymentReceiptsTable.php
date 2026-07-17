<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentReceiptsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'   => 'CHAR',
                'size'   => 36,
                'unique' => true,
            ],
            'payment_id' => [
                'type' => 'INT',
            ],
            'receipt_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'unique'     => true,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('payment_id', 'payments', 'id');
        $this->forge->addKey('payment_id');
        $this->forge->createTable('payment_receipts');
    }

    public function down()
    {
        $this->forge->dropTable('payment_receipts');
    }
}
