<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReferralsTable extends Migration
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
            'referral_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'destination' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'reason' => [
                'type' => 'TEXT',
            ],
            'referral_date' => [
                'type' => 'DATE',
            ],
            'referred_by' => [
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
        $this->forge->addForeignKey('referred_by', 'users', 'id');
        $this->forge->addKey('visit_id');
        $this->forge->addKey('patient_id');
        $this->forge->addKey('referred_by');
        $this->forge->addKey('referral_type');
        $this->forge->addKey('referral_date');
        $this->forge->createTable('referrals');
    }

    public function down()
    {
        $this->forge->dropTable('referrals');
    }
}
