<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true],
            'uuid' => ['type' => 'UUID', 'unique' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'is_system' => ['type' => 'BOOLEAN', 'default' => false],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('uuid');
        $this->forge->addKey('slug');
        $this->forge->createTable('roles');
    }

    public function down()
    {
        $this->forge->dropTable('roles');
    }
}
