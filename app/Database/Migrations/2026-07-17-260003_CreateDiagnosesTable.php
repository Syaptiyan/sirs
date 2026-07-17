<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDiagnosesTable extends Migration
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
            'medical_record_id' => [
                'type' => 'INT',
            ],
            'icd10_code_id' => [
                'type' => 'INT',
            ],
            'diagnosis_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
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
        $this->forge->addForeignKey('medical_record_id', 'medical_records', 'id');
        $this->forge->addForeignKey('icd10_code_id', 'icd10_codes', 'id');
        $this->forge->addKey('medical_record_id');
        $this->forge->addKey('icd10_code_id');
        $this->forge->createTable('diagnoses');
    }

    public function down()
    {
        $this->forge->dropTable('diagnoses');
    }
}
