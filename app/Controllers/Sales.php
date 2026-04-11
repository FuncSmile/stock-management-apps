<?php

namespace App\Controllers;

use App\Models\SalesTransactionModel;

class Sales extends BaseController
{
    public function receipt($batchId)
    {
        $salesModel = new SalesTransactionModel();
        
        // Fetch all items in this batch
        $transactions = $salesModel
            ->select('sales_transactions.*, items.name as item_name, items.sku as item_sku')
            ->join('items', 'items.id = sales_transactions.item_id')
            ->where('batch_id', $batchId)
            ->findAll();

        if (empty($transactions)) {
            return redirect()->to('scan')->with('error', 'Nota tidak ditemukan.');
        }

        // Fetch user data for the actor
        $userId = $transactions[0]['user_id'];
        $userModel = auth()->getProvider();
        $user = $userModel->findById($userId);
        
        $data = [
            'batch_id'     => $batchId,
            'transactions' => $transactions,
            'staff_name'   => $user ? $user->username : 'Staff',
            'created_at'   => $transactions[0]['created_at'],
            'total_amount' => array_sum(array_map(function($t) { 
                return $t['deal_price'] * $t['qty']; 
            }, $transactions))
        ];

        return view('sales/receipt', $data);
    }
}
