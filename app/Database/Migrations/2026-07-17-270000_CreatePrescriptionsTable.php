<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrescriptionsTable extends Migration
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
            'prescription_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'unique'     => true,
            ],
            'visit_id' => [
                'type' => 'INT',
            ],
            'patient_id' => [
                'type' => 'INT',
            ],
            'doctor_id' => [
                'type' => 'INT',
            ],
            'prescription_date' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pending',
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
        $this->forge->addForeignKey('visit_id', 'visits', 'id');
        $this->forge->addForeignKey('patient_id', 'patients', 'id');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id');
        $this->forge->addKey('visit_id');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('doctor_id');
        $this->forge->addKey('prescription_date');
        $this->forge->addKey('status');
        $this->forge->createTable('prescriptions');
    }

    public function down()
    {
        $this->forge->dropTable('prescriptions');
    }
}
