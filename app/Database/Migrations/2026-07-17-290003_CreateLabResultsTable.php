<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLabResultsTable extends Migration
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
            'order_id' => [
                'type' => 'INT',
            ],
            'result_date' => [
                'type' => 'DATE',
            ],
            'result_by' => [
                'type' => 'INT',
                'null'     => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addForeignKey('order_id', 'lab_orders', 'id');
        $this->forge->addForeignKey('result_by', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addKey('order_id');
        $this->forge->addKey('result_date');
        $this->forge->createTable('lab_results');
    }

    public function down()
    {
        $this->forge->dropTable('lab_results');
    }
}
