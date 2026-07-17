<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrescriptionDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'prescription_id' => [
                'type' => 'INT',
            ],
            'drug_id' => [
                'type' => 'INT',
            ],
            'quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'dosage' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'frequency' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'duration' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'instructions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'dispensed_quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
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
        $this->forge->addForeignKey('prescription_id', 'prescriptions', 'id');
        $this->forge->addForeignKey('drug_id', 'drugs', 'id');
        $this->forge->addKey('prescription_id');
        $this->forge->addKey('drug_id');
        $this->forge->createTable('prescription_details');
    }

    public function down()
    {
        $this->forge->dropTable('prescription_details');
    }
}
