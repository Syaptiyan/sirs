<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItemReceiptsTable extends Migration
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
            'item_id' => [
                'type' => 'INT',
            ],
            'warehouse_id' => [
                'type' => 'INT',
            ],
            'supplier_id' => [
                'type' => 'INT',
                'null'     => true,
            ],
            'quantity' => [
                'type' => 'INT',
            ],
            'receipt_date' => [
                'type' => 'DATE',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
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
        $this->forge->addForeignKey('item_id', 'items', 'id');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id');
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id');
        $this->forge->addForeignKey('created_by', 'users', 'id');
        $this->forge->addKey('item_id');
        $this->forge->addKey('warehouse_id');
        $this->forge->addKey('receipt_date');
        $this->forge->addKey('created_by');
        $this->forge->createTable('item_receipts');
    }

    public function down()
    {
        $this->forge->dropTable('item_receipts');
    }
}
