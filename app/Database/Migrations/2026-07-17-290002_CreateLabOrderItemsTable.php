<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLabOrderItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'order_id' => [
                'type' => 'INT',
            ],
            'template_id' => [
                'type' => 'INT',
                'null'     => true,
            ],
            'parameter_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'result_value' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'normal_range' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
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
        $this->forge->addForeignKey('template_id', 'lab_templates', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addKey('order_id');
        $this->forge->addKey('template_id');
        $this->forge->createTable('lab_order_items');
    }

    public function down()
    {
        $this->forge->dropTable('lab_order_items');
    }
}
