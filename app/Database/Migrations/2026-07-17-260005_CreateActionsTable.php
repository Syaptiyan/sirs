<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActionsTable extends Migration
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
            'medical_record_id' => [
                'type' => 'INT',
            ],
            'action_type_id' => [
                'type' => 'INT',
            ],
            'quantity' => [
                'type'    => 'INT',
                'default' => 1,
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
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
        $this->forge->addForeignKey('medical_record_id', 'medical_records', 'id');
        $this->forge->addForeignKey('action_type_id', 'action_types', 'id');
        $this->forge->addKey('medical_record_id');
        $this->forge->addKey('action_type_id');
        $this->forge->createTable('actions');
    }

    public function down()
    {
        $this->forge->dropTable('actions');
    }
}
