<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDoctorPolyclinicsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'doctor_id' => [
                'type' => 'INT',
            ],
            'polyclinic_id' => [
                'type' => 'INT',
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
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id');
        $this->forge->addForeignKey('polyclinic_id', 'polyclinics', 'id');
        $this->forge->addKey(['doctor_id', 'polyclinic_id'], false, true);
        $this->forge->createTable('doctor_polyclinics');
    }

    public function down()
    {
        $this->forge->dropTable('doctor_polyclinics');
    }
}