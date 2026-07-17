<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVisitsTable extends Migration
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
            'visit_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'unique'     => true,
            ],
            'patient_id' => [
                'type' => 'INT',
            ],
            'doctor_id' => [
                'type' => 'INT',
            ],
            'polyclinic_id' => [
                'type' => 'INT',
            ],
            'visit_type_id' => [
                'type' => 'INT',
            ],
            'room_id' => [
                'type' => 'INT',
                'null'     => true,
            ],
            'bed_id' => [
                'type' => 'INT',
                'null'     => true,
            ],
            'schedule_id' => [
                'type' => 'INT',
                'null'     => true,
            ],
            'queue_id' => [
                'type' => 'INT',
                'null'     => true,
            ],
            'visit_date' => [
                'type' => 'DATE',
            ],
            'visit_time' => [
                'type' => 'TIME',
            ],
            'complaint' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'waiting',
            ],
            'admission_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'discharge_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'notes' => [
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('patient_id', 'patients', 'id');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id');
        $this->forge->addForeignKey('polyclinic_id', 'polyclinics', 'id');
        $this->forge->addForeignKey('visit_type_id', 'visit_types', 'id');
        $this->forge->addForeignKey('room_id', 'rooms', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('bed_id', 'beds', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('doctor_id');
        $this->forge->addKey('polyclinic_id');
        $this->forge->addKey('visit_type_id');
        $this->forge->addKey('visit_date');
        $this->forge->addKey('status');
        $this->forge->createTable('visits');
    }

    public function down()
    {
        $this->forge->dropTable('visits');
    }
}
