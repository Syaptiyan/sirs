<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBedsTable extends Migration
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
            'room_id' => [
                'type' => 'INT',
            ],
            'bed_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'available',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('room_id', 'rooms', 'id');
        $this->forge->addKey('bed_number');
        $this->forge->addKey('status');
        $this->forge->createTable('beds');
    }

    public function down()
    {
        $this->forge->dropTable('beds');
    }
}
