<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalesTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'batch_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'item_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'qty' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'deal_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'total_profit' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('sales_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('sales_transactions');
    }
}
