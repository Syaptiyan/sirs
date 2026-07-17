<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInsuranceClaimsTable extends Migration
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
            'invoice_id' => [
                'type' => 'INT',
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
            'claim_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pending',
            ],
            'claim_date' => [
                'type' => 'DATE',
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
        $this->forge->addForeignKey('invoice_id', 'invoices', 'id');
        $this->forge->addForeignKey('patient_id', 'patients', 'id');
        $this->forge->addKey('invoice_id');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('status');
        $this->forge->addKey('claim_date');
        $this->forge->createTable('insurance_claims');
    }

    public function down()
    {
        $this->forge->dropTable('insurance_claims');
    }
}
