<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Libraries\QrGenerator;

class Items extends BaseController
{
    /**
     * Generate QR Code for a specific item
     * 
     * @param string $id Item ID (UUID)
     */
    public function generateQr($id)
    {
        $itemModel = new ItemModel();
        $item = $itemModel->find($id);

        if (!$item) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Item not found'
            ])->setStatusCode(404);
        }

        $qrGenerator = new QrGenerator();
        try {
            // Data encoded is the Item ID (UUID)
            $path = $qrGenerator->generate($item['id'], $item['sku']);
            
            // Update database
            $itemModel->update($id, ['qr_code_path' => $path]);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'QR Code generated successfully',
                'path' => base_url($path)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function index()
    {
        $itemModel = new ItemModel();
        $data['items'] = $itemModel->findAll();
        
        // Temporarily return JSON for testing if no view yet
        return $this->response->setJSON($data);
    }
}
