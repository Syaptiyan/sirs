<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDrugStocksTable extends Migration
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
                'type'    => 'INT',
                'default' => 0,
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
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
        $this->forge->addForeignKey('drug_id', 'drugs', 'id');
        $this->forge->addForeignKey('batch_id', 'drug_batches', 'id');
        $this->forge->addKey('drug_id');
        $this->forge->addKey('batch_id');
        $this->forge->createTable('drug_stocks');
    }

    public function down()
    {
        $this->forge->dropTable('drug_stocks');
    }
}
