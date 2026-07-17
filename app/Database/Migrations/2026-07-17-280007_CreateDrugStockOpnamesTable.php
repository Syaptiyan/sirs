<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDrugStockOpnamesTable extends Migration
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
            'system_quantity' => [
                'type' => 'INT',
            ],
            'actual_quantity' => [
                'type' => 'INT',
            ],
            'difference' => [
                'type' => 'INT',
            ],
            'opname_date' => [
                'type' => 'DATE',
            ],
            'notes' => [
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
        $this->forge->addForeignKey('created_by', 'users', 'id');
        $this->forge->addKey('drug_id');
        $this->forge->addKey('opname_date');
        $this->forge->addKey('created_by');
        $this->forge->createTable('drug_stock_opnames');
    }

    public function down()
    {
        $this->forge->dropTable('drug_stock_opnames');
    }
}
