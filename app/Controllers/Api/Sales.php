<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ItemModel;
use App\Models\SalesTransactionModel;
use CodeIgniter\API\ResponseTrait;

class Sales extends BaseController
{
    use ResponseTrait;

    /**
     * Process an authenticated sales transaction batch.
     * Expected JSON payload:
     * {
     *   "batch_id": "optional-uuid",
     *   "items": [
     *     { "sku": "SKU-01", "qty": 1, "deal_price": 150000 },
     *     ...
     *   ]
     * }
     */
    public function process()
    {
        $json = $this->request->getJSON();
        
        if (!$json || !isset($json->items) || empty($json->items)) {
            return $this->fail('Data transaksi tidak valid atau kosong.', 400);
        }

        $userId = auth()->id();
        if (!$userId) {
            return $this->failUnauthorized('Anda harus login untuk melakukan transaksi.');
        }

        $itemModel = new ItemModel();
        $salesModel = new SalesTransactionModel();
        $db = \Config\Database::connect();

        // Generate human-readable Invoice ID: NS-YYYYMMDD-XXX
        $todayPrefix = 'NS-' . date('Ymd') . '-';
        $countToday = $db->table('sales_transactions')
                         ->select('DISTINCT(batch_id)')
                         ->like('batch_id', $todayPrefix, 'after')
                         ->countAllResults();
        
        $nextSequence = str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);
        $batchId = $json->batch_id ?? ($todayPrefix . $nextSequence);

        $db->transStart();

        foreach ($json->items as $row) {
            $sku = $row->sku;
            $qty = (int)$row->qty;
            $dealPrice = (float)$row->deal_price;

            if ($qty <= 0) continue;

            // Fetch Item Data
            $item = $itemModel->where('sku', $sku)->first();

            if (!$item) {
                $db->transRollback();
                return $this->failNotFound("Barang dengan SKU {$sku} tidak ditemukan.");
            }

            // Check Stock
            if ($item['current_stock'] < $qty) {
                $db->transRollback();
                return $this->fail("Stok untuk {$item['name']} tidak mencukupi (Sisa: {$item['current_stock']}).", 400);
            }

            // Calculate Profit: (Deal - Base) * Qty
            $basePrice = (float)($item['base_price'] ?? 0);
            $profit = ($dealPrice - $basePrice) * $qty;

            // 1. Update Inventory Stock
            $itemModel->update($item['id'], [
                'current_stock' => $item['current_stock'] - $qty
            ]);

            // 2. Insert into Sales Transaction (Accounting)
            $salesModel->insert([
                'batch_id'     => $batchId,
                'user_id'      => $userId,
                'item_id'      => $item['id'],
                'qty'          => $qty,
                'deal_price'   => $dealPrice,
                'total_profit' => $profit,
                'created_at'   => date('Y-m-d H:i:s')
            ]);

            // 3. Record in Stock Transaction History (Audit Trail)
            $db->table('stock_transactions')->insert([
                'batch_id'   => $batchId,
                'item_id'    => $item['id'],
                'type'       => 'OUT',
                'quantity'   => $qty,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->fail('Gagal memproses transaksi database.', 500);
        }

        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Transaksi penjualan berhasil diproses.',
            'batch_id' => $batchId,
            'actor_id' => $userId
        ]);
    }
}
