<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvoiceItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'invoice_id' => [
                'type' => 'INT',
            ],
            'item_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'item_id' => [
                'type' => 'INT',
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'quantity' => [
                'type'    => 'INT',
                'default' => 1,
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
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
        $this->forge->addKey('invoice_id');
        $this->forge->addKey('item_type');
        $this->forge->createTable('invoice_items');
    }

    public function down()
    {
        $this->forge->dropTable('invoice_items');
    }
}
