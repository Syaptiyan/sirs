<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDoctorSchedulesTable extends Migration
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
            'doctor_id' => [
                'type' => 'INT',
            ],
            'polyclinic_id' => [
                'type' => 'INT',
            ],
            'day_of_week' => [
                'type'    => 'SMALLINT',
                'default' => 0,
            ],
            'start_time' => [
                'type' => 'TIME',
            ],
            'end_time' => [
                'type' => 'TIME',
            ],
            'quota' => [
                'type'    => 'INT',
                'default' => 20,
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id');
        $this->forge->addForeignKey('polyclinic_id', 'polyclinics', 'id');
        $this->forge->addKey('doctor_id');
        $this->forge->addKey('polyclinic_id');
        $this->forge->addKey('day_of_week');
        $this->forge->addKey('is_active');
        $this->forge->createTable('doctor_schedules');
    }

    public function down()
    {
        $this->forge->dropTable('doctor_schedules');
    }
}
