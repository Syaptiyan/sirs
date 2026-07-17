<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScheduleExceptionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'doctor_id' => [
                'type' => 'INT',
            ],
            'exception_date' => [
                'type' => 'DATE',
            ],
            'is_holiday' => [
                'type'    => 'BOOLEAN',
                'default' => true,
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
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id');
        $this->forge->addKey('doctor_id');
        $this->forge->addKey('exception_date');
        $this->forge->createTable('schedule_exceptions');
    }

    public function down()
    {
        $this->forge->dropTable('schedule_exceptions');
    }
}
