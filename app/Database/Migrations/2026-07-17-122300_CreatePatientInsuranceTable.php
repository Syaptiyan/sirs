<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePatientInsuranceTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'patient_id' => [
                'type' => 'INT',
            ],
            'insurance_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'policy_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'expiry_date' => [
                'type' => 'DATE',
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
        $this->forge->createTable('patient_insurance');
    }

    public function down()
    {
        $this->forge->dropTable('patient_insurance');
    }
}
