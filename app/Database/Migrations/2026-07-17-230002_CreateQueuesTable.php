<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQueuesTable extends Migration
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
            'queue_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
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
            'visit_date' => [
                'type' => 'DATE',
            ],
            'queue_date' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'waiting',
            ],
            'priority' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'called_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'completed_at' => [
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('patient_id', 'patients', 'id');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id');
        $this->forge->addForeignKey('polyclinic_id', 'polyclinics', 'id');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('doctor_id');
        $this->forge->addKey('polyclinic_id');
        $this->forge->addKey('visit_date');
        $this->forge->addKey('queue_date');
        $this->forge->addKey('status');
        $this->forge->createTable('queues');
    }

    public function down()
    {
        $this->forge->dropTable('queues');
    }
}
