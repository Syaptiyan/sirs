<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDrugReturnsTable extends Migration
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
            'batch_id' => [
                'type' => 'INT',
            ],
            'quantity' => [
                'type' => 'INT',
            ],
            'return_date' => [
                'type' => 'DATE',
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
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
        $this->forge->addForeignKey('batch_id', 'drug_batches', 'id');
        $this->forge->addForeignKey('created_by', 'users', 'id');
        $this->forge->addKey('drug_id');
        $this->forge->addKey('batch_id');
        $this->forge->addKey('return_date');
        $this->forge->addKey('created_by');
        $this->forge->createTable('drug_returns');
    }

    public function down()
    {
        $this->forge->dropTable('drug_returns');
    }
}
