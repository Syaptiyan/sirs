<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItemStocksTable extends Migration
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
            'quantity' => [
                'type'    => 'INT',
                'default' => 0,
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
        $this->forge->addKey('item_id');
        $this->forge->addKey('warehouse_id');
        $this->forge->createTable('item_stocks');
    }

    public function down()
    {
        $this->forge->dropTable('item_stocks');
    }
}
