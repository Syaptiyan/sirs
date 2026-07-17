<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLabNormalRangesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'template_id' => [
                'type' => 'INT',
            ],
            'parameter_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'min_value' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'max_value' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'gender' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'age_min' => [
                'type' => 'INT',
                'null' => true,
            ],
            'age_max' => [
                'type' => 'INT',
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
        $this->forge->addForeignKey('template_id', 'lab_templates', 'id');
        $this->forge->addKey('template_id');
        $this->forge->addKey('parameter_name');
        $this->forge->createTable('lab_normal_ranges');
    }

    public function down()
    {
        $this->forge->dropTable('lab_normal_ranges');
    }
}
