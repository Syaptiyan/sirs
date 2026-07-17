<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDrugBatchesTable extends Migration
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
            'drug_id' => [
                'type' => 'INT',
            ],
            'batch_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'expiry_date' => [
                'type' => 'DATE',
            ],
            'quantity' => [
                'type'    => 'INT',
                'default' => 0,
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
        $this->forge->addForeignKey('drug_id', 'drugs', 'id');
        $this->forge->addKey('drug_id');
        $this->forge->addKey('batch_number');
        $this->forge->addKey('expiry_date');
        $this->forge->createTable('drug_batches');
    }

    public function down()
    {
        $this->forge->dropTable('drug_batches');
    }
}
