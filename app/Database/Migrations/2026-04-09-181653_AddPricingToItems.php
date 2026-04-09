<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPricingToItems extends Migration
{
    public function up()
    {
        $fields = [
            'base_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
                'after'      => 'name'
            ],
            'mark_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
                'after'      => 'base_price'
            ],
        ];
        $this->forge->addColumn('items', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('items', ['base_price', 'mark_price']);
    }
}
