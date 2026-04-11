<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Edit Barang<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <a href="<?= base_url('items') ?>" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Daftar
    </a>
    <h1 class="text-3xl font-bold text-slate-900">Edit Barang: <?= $item['name'] ?></h1>
    <p class="text-slate-500 mt-1">Perbarui informasi barang di bawah ini.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-8 h-fit">
        <form action="<?= base_url('items/update/' . $item['id']) ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>
            
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl">
                    <ul class="list-disc list-inside text-sm text-rose-600 space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="space-y-2">
                <label for="sku" class="text-sm font-bold text-slate-700 ml-1">SKU (Stock Keeping Unit)</label>
                <input type="text" name="sku" id="sku" value="<?= old('sku', $item['sku']) ?>" 
                    class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                    placeholder="Misal: BRG-001" required>
                <p class="text-xs text-amber-500 ml-1 font-medium">Mengubah SKU akan meregenerasi file QR Code.</p>
            </div>

            <div class="space-y-2">
                <label for="name" class="text-sm font-bold text-slate-700 ml-1">Nama Barang</label>
                <input type="text" name="name" id="name" value="<?= old('name', $item['name']) ?>"
                    class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                    placeholder="Masukkan nama barang..." required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="base_price" class="text-sm font-bold text-slate-700 ml-1">Harga Modal (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                        <input type="number" name="base_price" id="base_price" value="<?= old('base_price', $item['base_price']) ?>"
                            class="w-full pl-12 pr-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                            placeholder="0" required>
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="mark_price" class="text-sm font-bold text-slate-700 ml-1">Harga Jual (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                        <input type="number" name="mark_price" id="mark_price" value="<?= old('mark_price', $item['mark_price']) ?>"
                            class="w-full pl-12 pr-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                            placeholder="0" required>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="min_stock" class="text-sm font-bold text-slate-700 ml-1">Stok Minimum (Alert)</label>
                    <input type="number" name="min_stock" id="min_stock" value="<?= old('min_stock', $item['min_stock']) ?>"
                        class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                        required>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-400 ml-1">Stok Saat Ini</label>
                    <input type="text" value="<?= $item['current_stock'] ?>" disabled
                        class="w-full px-5 py-3 bg-slate-100 border border-slate-100 rounded-2xl text-slate-400 cursor-not-allowed">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- QR Code Preview Right Side -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 flex flex-col items-center justify-center text-center">
        <h3 class="text-sm font-bold text-slate-700 mb-6">QR Code Barang</h3>
        <?php if ($item['qr_code_path'] && file_exists(FCPATH . $item['qr_code_path'])): ?>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 mb-6 transition-all hover:scale-105 duration-300">
                <img src="<?= base_url($item['qr_code_path']) ?>" alt="QR Code" class="w-48 h-48 rounded-lg">
            </div>
            <a href="<?= base_url($item['qr_code_path']) ?>" download="QR_<?= $item['sku'] ?>.png" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PNG
            </a>
        <?php else: ?>
            <div class="w-48 h-48 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center mb-6">
                <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 17h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                <p class="text-xs text-slate-400">Belum ada QR</p>
            </div>
            <a href="<?= base_url('items/generate-qr/' . $item['id']) ?>" class="text-sm font-semibold text-amber-600 hover:text-amber-700">Generate Sekarang</a>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
