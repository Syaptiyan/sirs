<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDrugsTable extends Migration
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
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'unique'     => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
            ],
            'generic_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'category_id' => [
                'type' => 'INT',
            ],
            'form' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'strength' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'manufacturer' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'buy_price' => [
                'type'    => 'DECIMAL',
                'constraint' => 15,
                'scale'      => 2,
                'default'    => 0,
            ],
            'sell_price' => [
                'type'    => 'DECIMAL',
                'constraint' => 15,
                'scale'      => 2,
                'default'    => 0,
            ],
            'min_stock' => [
                'type'    => 'INT',
                'default' => 0,
            ],
            'is_active' => [
                'type'    => 'TINYINT',
                'size'    => 1,
                'default' => 1,
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
        $this->forge->addForeignKey('category_id', 'drug_categories', 'id');
        $this->forge->addKey('code');
        $this->forge->addKey('name');
        $this->forge->addKey('category_id');
        $this->forge->addKey('is_active');
        $this->forge->createTable('drugs');
    }

    public function down()
    {
        $this->forge->dropTable('drugs');
    }
}
