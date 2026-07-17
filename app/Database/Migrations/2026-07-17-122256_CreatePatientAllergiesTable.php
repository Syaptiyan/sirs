<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePatientAllergiesTable extends Migration
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
            'allergen' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'reaction' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'severity' => [
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('patient_id', 'patients', 'id');
        $this->forge->createTable('patient_allergies');
    }

    public function down()
    {
        $this->forge->dropTable('patient_allergies');
    }
}
