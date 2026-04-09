<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'            => '550e8400-e29b-41d4-a716-446655440000',
                'sku'           => 'BRG-001',
                'name'          => 'Kertas A4 80gr',
                'current_stock' => 50,
                'min_stock'     => 10,
                'qr_code_path'  => 'public/uploads/qr/BRG-001.png',
            ],
            [
                'id'            => '550e8400-e29b-41d4-a716-446655440001',
                'sku'           => 'BRG-002',
                'name'          => 'Tinta Printer Epson Black',
                'current_stock' => 15,
                'min_stock'     => 5,
                'qr_code_path'  => 'public/uploads/qr/BRG-002.png',
            ],
            [
                'id'            => '550e8400-e29b-41d4-a716-446655440002',
                'sku'           => 'BRG-003',
                'name'          => 'Pulpen Gel Black 0.5',
                'current_stock' => 5,
                'min_stock'     => 12,
                'qr_code_path'  => 'public/uploads/qr/BRG-003.png',
            ],
        ];

        // Using Query Builder
        $this->db->table('items')->insertBatch($data);
    }
}
