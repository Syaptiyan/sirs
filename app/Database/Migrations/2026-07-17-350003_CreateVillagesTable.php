<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVillagesTable extends Migration
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
            'district_id' => [
                'type' => 'INT',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'postal_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
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
        $this->forge->addForeignKey('district_id', 'districts', 'id');
        $this->forge->addKey('district_id');
        $this->forge->addKey('name');
        $this->forge->addKey('postal_code');
        $this->forge->createTable('villages');
    }

    public function down()
    {
        $this->forge->dropTable('villages');
    }
}
