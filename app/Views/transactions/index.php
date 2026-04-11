<?php
/**
 * View for Global Transactions (Sales History)
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Transaksi Global<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Transaksi Global</h1>
        <p class="text-slate-500 mt-1">Riwayat lengkap penjualan dan performa toko.</p>
    </div>
    <div class="flex space-x-3">
        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center shadow-sm transition-all hover:shadow-md">
            <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="flex flex-col">
                <span class="text-[10px] uppercase font-bold text-emerald-400 leading-none mb-1">Total Penjualan (Hari Ini)</span>
                <span class="text-sm font-black text-emerald-700 leading-none">
                    Rp <?= number_format(array_sum(array_map(function($t) { 
                        return (date('Y-m-d', strtotime($t['created_at'])) === date('Y-m-d')) ? ($t['deal_price'] * $t['qty']) : 0; 
                    }, $transactions)), 0, ',', '.') ?>
                </span>
            </div>
        </div>

        <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-center shadow-sm transition-all hover:shadow-md">
            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            <div class="flex flex-col">
                <span class="text-[10px] uppercase font-bold text-indigo-400 leading-none mb-1">Estimasi Profit (Hari Ini)</span>
                <span class="text-sm font-black text-indigo-700 leading-none">
                    Rp <?= number_format(array_sum(array_map(function($t) { 
                        return (date('Y-m-d', strtotime($t['created_at'])) === date('Y-m-d')) ? $t['total_profit'] : 0; 
                    }, $transactions)), 0, ',', '.') ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Nota / Tanggal</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Aktor</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Barang</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-right">Harga Nego</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-right text-emerald-600">Profit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($transactions as $t): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-indigo-600 font-mono"><?= $t['batch_id'] ?></span>
                                <span class="text-[10px] text-slate-400 mt-0.5"><?= date('d M Y, H:i', strtotime($t['created_at'])) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-[10px] font-bold mr-3 border border-slate-200">
                                    <?= substr($userMap[$t['user_id']] ?? 'U', 0, 1) ?>
                                </div>
                                <span class="text-xs font-bold text-slate-700"><?= $userMap[$t['user_id']] ?? 'Unknown' ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900"><?= $t['item_name'] ?></span>
                                <span class="text-[10px] text-slate-400 font-mono uppercase"><?= $t['item_sku'] ?> &times; <?= $t['qty'] ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <span class="text-xs font-bold text-slate-700">Rp <?= number_format($t['deal_price'], 0, ',', '.') ?></span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-black tracking-tight border border-emerald-100">
                                + Rp <?= number_format($t['total_profit'], 0, ',', '.') ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-4 text-slate-200 border-2 border-dashed border-slate-100">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada transaksi terekam</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
