<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\SalesTransactionModel;

class Home extends BaseController
{
    public function index()
    {
        // Redirect Staff to scanning page if they try to access dashboard
        if (! auth()->loggedIn()) {
            return redirect()->to('login');
        }

        if (! auth()->user()->inGroup('owner')) {
            return redirect()->to('scan');
        }

        $itemModel = new ItemModel();
        $db = \Config\Database::connect();
        
        // Get period from filter (daily or monthly)
        $period = $this->request->getGet('period') ?? 'monthly';
        
        // 1. Total Items
        $data['total_items'] = $itemModel->countAllResults();
        
        // 2. Low Stock Count
        $data['low_stock_count'] = $itemModel->where('current_stock <= min_stock')->countAllResults();
        
        // 3. Transactions & Sales Count based on period
        $builder = $db->table('stock_transactions');
        $salesBuilder = $db->table('sales_transactions');
        
        if ($period === 'daily') {
            $builder->where('DATE(created_at)', date('Y-m-d'));
            $salesBuilder->where('DATE(created_at)', date('Y-m-d'));
        } else {
            $builder->where('MONTH(created_at)', date('m'))
                    ->where('YEAR(created_at)', date('Y'));
            $salesBuilder->where('MONTH(created_at)', date('m'))
                         ->where('YEAR(created_at)', date('Y'));
        }
        
        // Stats for IN
        $inBuilder = clone $builder;
        $data['total_in'] = $inBuilder->where('type', 'IN')->selectSum('quantity')->get()->getRow()?->quantity ?? 0;
        
        // Stats for OUT
        $outBuilder = clone $builder;
        $data['total_out'] = $outBuilder->where('type', 'OUT')->selectSum('quantity')->get()->getRow()?->quantity ?? 0;

        // Stats for SALES (Revenue and Profit)
        $revenueBuilder = clone $salesBuilder;
        $data['total_revenue'] = $revenueBuilder->select('SUM(deal_price * qty) as revenue')->get()->getRow()?->revenue ?? 0;
        
        $profitBuilder = clone $salesBuilder;
        $data['total_profit'] = $profitBuilder->selectSum('total_profit')->get()->getRow()?->total_profit ?? 0;
        
        // 4. Recent Activity (Last 5)
        $data['recent_activity'] = $db->table('stock_transactions')
            ->select('stock_transactions.*, items.name as item_name')
            ->join('items', 'items.id = stock_transactions.item_id')
            ->orderBy('stock_transactions.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
            
        $data['current_period'] = $period;

        // 5. Chart Preparation (Last 7 Days)
        $chartData = [];
        $salesChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chartData[$date] = ['IN' => 0, 'OUT' => 0];
            $salesChartData[$date] = ['revenue' => 0, 'profit' => 0];
        }

        // Fetch Stock Movement
        $rawChart = $db->table('stock_transactions')
            ->select('DATE(created_at) as date, type, SUM(quantity) as total')
            ->where('created_at >=', date('Y-m-d 00:00:00', strtotime('-6 days')))
            ->groupBy('DATE(created_at), type')
            ->get()
            ->getResultArray();

        foreach ($rawChart as $row) {
            $date = $row['date'];
            if (isset($chartData[$date])) {
                $chartData[$date][$row['type']] = (int)$row['total'];
            }
        }

        // Fetch Sales Movement
        $rawSalesChart = $db->table('sales_transactions')
            ->select('DATE(created_at) as date, SUM(deal_price * qty) as revenue, SUM(total_profit) as profit')
            ->where('created_at >=', date('Y-m-d 00:00:00', strtotime('-6 days')))
            ->groupBy('DATE(created_at)')
            ->get()
            ->getResultArray();

        foreach ($rawSalesChart as $row) {
            $date = $row['date'];
            if (isset($salesChartData[$date])) {
                $salesChartData[$date]['revenue'] = (float)$row['revenue'];
                $salesChartData[$date]['profit']  = (float)$row['profit'];
            }
        }

        // Finalize Data for View
        $data['chart_labels']  = [];
        $data['chart_in']      = [];
        $data['chart_out']     = [];
        $data['sales_revenue'] = [];
        $data['sales_profit']  = [];
        
        foreach ($chartData as $date => $values) {
            $data['chart_labels'][] = date('d M', strtotime($date));
            $data['chart_in'][]      = $values['IN'];
            $data['chart_out'][]     = $values['OUT'];
            $data['sales_revenue'][] = $salesChartData[$date]['revenue'];
            $data['sales_profit'][]  = $salesChartData[$date]['profit'];
        }

        return view('dashboard', $data);
    }
}
