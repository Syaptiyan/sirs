<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDoctorRoundsTable extends Migration
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
            'visit_id' => [
                'type' => 'INT',
            ],
            'doctor_id' => [
                'type' => 'INT',
            ],
            'round_date' => [
                'type' => 'DATE',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'instructions' => [
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
        $this->forge->addForeignKey('visit_id', 'visits', 'id');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id');
        $this->forge->addKey('visit_id');
        $this->forge->addKey('doctor_id');
        $this->forge->addKey('round_date');
        $this->forge->createTable('doctor_rounds');
    }

    public function down()
    {
        $this->forge->dropTable('doctor_rounds');
    }
}
