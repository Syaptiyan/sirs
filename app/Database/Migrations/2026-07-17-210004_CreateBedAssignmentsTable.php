<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBedAssignmentsTable extends Migration
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
            'bed_id' => [
                'type' => 'INT',
            ],
            'patient_id' => [
                'type' => 'INT',
            ],
            'visit_id' => [
                'type' => 'INT',
                'null'     => true,
            ],
            'assigned_at' => [
                'type' => 'DATETIME',
            ],
            'released_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active',
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
        $this->forge->addForeignKey('bed_id', 'beds', 'id');
        $this->forge->addForeignKey('patient_id', 'patients', 'id');
        $this->forge->addKey('assigned_at');
        $this->forge->addKey('status');
        $this->forge->createTable('bed_assignments');
    }

    public function down()
    {
        $this->forge->dropTable('bed_assignments');
    }
}
