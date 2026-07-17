<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBloodTypesTable extends Migration
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
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'unique'     => true,
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
        $this->forge->createTable('blood_types');
    }

    public function down()
    {
        $this->forge->dropTable('blood_types');
    }
}
