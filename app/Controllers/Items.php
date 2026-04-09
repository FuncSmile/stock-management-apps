<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Libraries\QrGenerator;

class Items extends BaseController
{
    public function index()
    {
        $itemModel = new ItemModel();
        $data['items'] = $itemModel->findAll();
        
        return view('items/index', $data);
    }

    public function new()
    {
        return view('items/create');
    }

    public function create()
    {
        $rules = [
            'sku'       => 'required|is_unique[items.sku]',
            'name'      => 'required|min_length[3]',
            'min_stock' => 'required|is_natural',
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
            'sku'       => "required|is_unique[items.sku,id,{$id}]",
            'name'      => 'required|min_length[3]',
            'min_stock' => 'required|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'sku'       => $this->request->getPost('sku'),
            'name'      => $this->request->getPost('name'),
            'min_stock' => $this->request->getPost('min_stock'),
        ];

        if ($itemModel->update($id, $data)) {
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
