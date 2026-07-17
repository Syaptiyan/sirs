<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDistrictsTable extends Migration
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
            'city_id' => [
                'type' => 'INT',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
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
        $this->forge->addForeignKey('city_id', 'cities', 'id');
        $this->forge->addKey('city_id');
        $this->forge->addKey('name');
        $this->forge->createTable('districts');
    }

    public function down()
    {
        $this->forge->dropTable('districts');
    }
}
