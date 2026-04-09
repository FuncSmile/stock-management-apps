<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Daftar Barang<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Daftar Barang</h1>
        <p class="text-slate-500 mt-1">Kelola informasi barang dan generate QR Code.</p>
    </div>
    <a href="<?= base_url('items/new') ?>" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Barang
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center text-green-700">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-sm font-bold"><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center text-rose-700">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        <span class="text-sm font-bold"><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<!-- Table Card -->
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">QR</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Stok Saat Ini</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($items as $item): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-mono text-sm font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md"><?= $item['sku'] ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-slate-900"><?= $item['name'] ?></p>
                        <p class="text-[10px] text-slate-400 font-mono"><?= $item['id'] ?></p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($item['qr_code_path'] && file_exists(FCPATH . $item['qr_code_path'])): ?>
                            <img src="<?= base_url($item['qr_code_path']) ?>" alt="QR" class="w-10 h-10 rounded border border-slate-100 mx-auto">
                        <?php else: ?>
                            <div class="w-10 h-10 bg-slate-50 rounded border border-dashed border-slate-200 mx-auto flex items-center justify-center text-[8px] text-slate-400">No QR</div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                             <p class="text-sm font-bold <?= $item['current_stock'] <= $item['min_stock'] ? 'text-rose-600' : 'text-slate-700' ?>">
                                <?= $item['current_stock'] ?>
                            </p>
                            <?php if ($item['current_stock'] <= $item['min_stock']): ?>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold bg-rose-50 text-rose-600 uppercase">Rendah</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= base_url('items/edit/' . $item['id']) ?>" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <a href="<?= base_url('items/delete/' . $item['id']) ?>" 
                               class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" 
                               title="Hapus"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p class="text-slate-400 text-sm">Belum ada data barang.</p>
                            <a href="<?= base_url('items/new') ?>" class="text-indigo-600 text-sm font-bold mt-2 hover:underline">Tambah Sekarang</a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
