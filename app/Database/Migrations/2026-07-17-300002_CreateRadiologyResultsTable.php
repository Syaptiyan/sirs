<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRadiologyResultsTable extends Migration
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
            'result_text' => [
                'type' => 'TEXT',
            ],
            'impression' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'result_date' => [
                'type' => 'DATE',
            ],
            'result_by' => [
                'type' => 'INT',
                'null'     => true,
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
        $this->forge->addForeignKey('order_id', 'radiology_orders', 'id');
        $this->forge->addForeignKey('result_by', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addKey('order_id');
        $this->forge->addKey('result_date');
        $this->forge->createTable('radiology_results');
    }

    public function down()
    {
        $this->forge->dropTable('radiology_results');
    }
}
