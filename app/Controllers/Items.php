<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\AuditLogModel;
use App\Libraries\QrGenerator;

class Items extends BaseController
{
    public function index()
    {
        $itemModel = new ItemModel();
        
        $search = $this->request->getGet('search');
        if ($search) {
            $itemModel->groupStart()
                ->like('name', $search)
                ->orLike('sku', $search)
                ->groupEnd();
        }
        
        $data['items'] = $itemModel->findAll();
        $data['search'] = $search;
        
        return view('items/index', $data);
    }

    public function new()
    {
        return view('items/create');
    }

    public function create()
    {
        $rules = [
            'sku'        => 'required|is_unique[items.sku]',
            'name'       => 'required|min_length[3]',
            'base_price' => 'required|numeric',
            'mark_price' => 'required|numeric',
            'min_stock'  => 'required|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $itemModel = new ItemModel();
        
        // Simple UUID v4 generation
        $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );

        $data = [
            'id'            => $id,
            'sku'           => $this->request->getPost('sku'),
            'name'          => $this->request->getPost('name'),
            'base_price'    => $this->request->getPost('base_price'),
            'mark_price'    => $this->request->getPost('mark_price'),
            'current_stock' => 0,
            'min_stock'     => $this->request->getPost('min_stock'),
        ];

        if ($itemModel->insert($data)) {
            // Trigger QR Generation internal call
            $this->_generateQrInternal($id);
            return redirect()->to('items')->with('success', 'Barang berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan barang.');
    }

    public function edit($id)
    {
        $itemModel = new ItemModel();
        $data['item'] = $itemModel->find($id);

        if (!$data['item']) {
            return redirect()->to('items')->with('error', 'Barang tidak ditemukan.');
        }

        return view('items/edit', $data);
    }

    public function update($id)
    {
        $itemModel = new ItemModel();
        $item = $itemModel->find($id);

        if (!$item) {
            return redirect()->to('items')->with('error', 'Barang tidak ditemukan.');
        }

        $rules = [
            'sku'        => "required|is_unique[items.sku,id,{$id}]",
            'name'       => 'required|min_length[3]',
            'base_price' => 'required|numeric',
            'mark_price' => 'required|numeric',
            'min_stock'  => 'required|is_natural',
        ];

        // Add reason validation if user is owner and stock is changing
        $isOwner = auth()->user()->inGroup('owner');
        if ($isOwner && $this->request->getPost('current_stock') !== null) {
            $newStock = (int)$this->request->getPost('current_stock');
            if ($newStock !== (int)$item['current_stock']) {
                $rules['adjustment_reason'] = 'required|min_length[3]';
            }
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'sku'        => $this->request->getPost('sku'),
            'name'       => $this->request->getPost('name'),
            'base_price' => $this->request->getPost('base_price'),
            'mark_price' => $this->request->getPost('mark_price'),
            'min_stock'  => $this->request->getPost('min_stock'),
        ];

        // Capture stock update for Owner
        $stockChanged = false;
        if ($isOwner && $this->request->getPost('current_stock') !== null) {
            $newStock = (int)$this->request->getPost('current_stock');
            if ($newStock !== (int)$item['current_stock']) {
                $data['current_stock'] = $newStock;
                $stockChanged = true;
            }
        }

        if ($itemModel->update($id, $data)) {
            // Log manual stock adjustment
            if ($stockChanged) {
                AuditLogModel::log("Manual Stock Adjustment: {$item['name']}", [
                    'item_id'   => $id,
                    'old_stock' => $item['current_stock'],
                    'new_stock' => $data['current_stock'],
                    'reason'    => $this->request->getPost('adjustment_reason')
                ]);
            }

            // Regenerate QR if SKU changed
            if ($item['sku'] !== $data['sku']) {
                // Delete old QR if exists
                if ($item['qr_code_path'] && file_exists(FCPATH . $item['qr_code_path'])) {
                    unlink(FCPATH . $item['qr_code_path']);
                }
                $this->_generateQrInternal($id);
            }
            return redirect()->to('items')->with('success', 'Barang berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui barang.');
    }

    public function delete($id)
    {
        $itemModel = new ItemModel();
        $item = $itemModel->find($id);

        if ($item) {
            // Delete QR file
            if ($item['qr_code_path'] && file_exists(FCPATH . $item['qr_code_path'])) {
                unlink(FCPATH . $item['qr_code_path']);
            }
            $itemModel->delete($id);
            return redirect()->to('items')->with('success', 'Barang berhasil dihapus.');
        }

        return redirect()->to('items')->with('error', 'Barang tidak ditemukan.');
    }

    /**
     * Public endpoint for manual QR generation
     */
    public function generateQr($id)
    {
        try {
            $path = $this->_generateQrInternal($id);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'QR Code generated successfully',
                'path' => base_url($path)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => $e->getMessage()
            ])->setStatusCode(404);
        }
    }

    /**
     * Get item info by SKU (API helper)
     */
    public function info($search)
    {
        $itemModel = new ItemModel();
        // Search by ID (UUID) or SKU
        $item = $itemModel->where('id', $search)
                          ->orWhere('sku', $search)
                          ->first();

        if (!$item) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Item tidak ditemukan'
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'id' => $item['id'],
                'sku' => $item['sku'],
                'name' => $item['name'],
                'current_stock' => $item['current_stock'],
                'mark_price' => $item['mark_price']
            ]
        ]);
    }

    /**
     * Batch update stock (Implementation for Issue #6)
     */
    public function batchUpdate()
    {
        $json = $this->request->getJSON();
        
        if (!$json || !isset($json->items) || empty($json->items)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak valid atau kosong.'
            ])->setStatusCode(400);
        }

        $type = $json->type ?? 'IN';
        $itemModel = new ItemModel();
        $db = \Config\Database::connect();
        
        $db->transStart();

        $batchId = 'BATCH-' . date('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(2)));

        foreach ($json->items as $itemData) {
            $sku = $itemData->sku;
            $qty = (int)$itemData->qty;

            if ($qty <= 0) continue;

            $item = $itemModel->where('sku', $sku)->first();
            
            if (!$item) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => "Barang dengan SKU {$sku} tidak ditemukan."
                ])->setStatusCode(404);
            }

            // Calculation
            $newStock = ($type === 'IN') 
                ? $item['current_stock'] + $qty 
                : $item['current_stock'] - $qty;

            if ($type === 'OUT' && $newStock < 0) {
                $db->transRollback();
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => "Stok untuk {$item['name']} tidak mencukupi (Sisa: {$item['current_stock']})."
                ])->setStatusCode(400);
            }

            // Update Item Stock
            $itemModel->update($item['id'], ['current_stock' => $newStock]);

            // Record Transaction
            $db->table('stock_transactions')->insert([
                'batch_id'   => $batchId,
                'item_id'    => $item['id'],
                'type'       => $type,
                'quantity'   => $qty,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memproses transaksi database.'
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "Berhasil memproses {$type} stok untuk " . count($json->items) . " jenis barang.",
            'batch_id' => $batchId
        ]);
    }

    /**
     * Internal logic for QR generation
     */
    private function _generateQrInternal($id)
    {
        $itemModel = new ItemModel();
        $item = $itemModel->find($id);

        if (!$item) {
            throw new \Exception('Item not found');
        }

        $qrGenerator = new QrGenerator();
        $path = $qrGenerator->generate($item['id'], $item['sku']);
        
        $itemModel->update($id, ['qr_code_path' => $path]);

        return $path;
    }
}
