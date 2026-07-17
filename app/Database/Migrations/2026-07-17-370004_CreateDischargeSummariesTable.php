<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDischargeSummariesTable extends Migration
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
            'patient_id' => [
                'type' => 'INT',
            ],
            'diagnosis' => [
                'type' => 'TEXT',
            ],
            'treatment_summary' => [
                'type' => 'TEXT',
            ],
            'follow_up_instructions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'discharge_date' => [
                'type' => 'DATE',
            ],
            'discharged_by' => [
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
        $this->forge->addForeignKey('visit_id', 'visits', 'id');
        $this->forge->addForeignKey('patient_id', 'patients', 'id');
        $this->forge->addForeignKey('discharged_by', 'users', 'id');
        $this->forge->addKey('visit_id');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('discharged_by');
        $this->forge->addKey('discharge_date');
        $this->forge->createTable('discharge_summaries');
    }

    public function down()
    {
        $this->forge->dropTable('discharge_summaries');
    }
}
