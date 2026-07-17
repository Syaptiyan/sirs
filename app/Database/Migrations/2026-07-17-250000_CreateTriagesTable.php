<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTriagesTable extends Migration
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
            'triage_level' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'chief_complaint' => [
                'type' => 'TEXT',
            ],
            'vital_signs' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'consciousness_level' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'alert',
            ],
            'pain_scale' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'triaged_by' => [
                'type' => 'INT',
            ],
            'triaged_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addForeignKey('triaged_by', 'users', 'id');
        $this->forge->addKey('visit_id');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('triage_level');
        $this->forge->addKey('triaged_at');
        $this->forge->createTable('triages');
    }

    public function down()
    {
        $this->forge->dropTable('triages');
    }
}
