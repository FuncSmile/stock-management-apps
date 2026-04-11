<?php

namespace App\Controllers;

use App\Models\SalesTransactionModel;

class Transactions extends BaseController
{
    public function index()
    {
        // Restrict to owner
        if (!auth()->user()->inGroup('owner')) {
            return redirect()->to('scan')->with('error', 'Akses ditolak.');
        }

        $salesModel = new SalesTransactionModel();
        
        // Fetch transactions with item and user details
        $transactions = $salesModel
            ->select('sales_transactions.*, items.name as item_name, items.sku as item_sku')
            ->join('items', 'items.id = sales_transactions.item_id')
            ->orderBy('sales_transactions.created_at', 'DESC')
            ->findAll(200);

        // Fetch user data from Shield's user provider
        $userIds = array_unique(array_column($transactions, 'user_id'));
        $userModel = auth()->getProvider();
        $userMap = [];
        
        if (!empty($userIds)) {
            foreach ($userIds as $id) {
                $user = $userModel->findById($id);
                $userMap[$id] = $user ? $user->username : 'Unknown';
            }
        }

        $data = [
            'transactions' => $transactions,
            'userMap' => $userMap
        ];

        return view('transactions/index', $data);
    }
}
