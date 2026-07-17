<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQueueDisplaysTable extends Migration
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
            'polyclinic_id' => [
                'type' => 'INT',
            ],
            'display_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'current_queue_id' => [
                'type' => 'INT',
                'null'     => true,
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
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
        $this->forge->addForeignKey('polyclinic_id', 'polyclinics', 'id');
        $this->forge->addForeignKey('current_queue_id', 'queues', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addKey('polyclinic_id');
        $this->forge->addKey('is_active');
        $this->forge->createTable('queue_displays');
    }

    public function down()
    {
        $this->forge->dropTable('queue_displays');
    }
}
