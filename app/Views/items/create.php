<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Tambah Barang Baru<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <a href="<?= base_url('items') ?>" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Daftar
    </a>
    <h1 class="text-3xl font-bold text-slate-900">Tambah Barang Baru</h1>
    <p class="text-slate-500 mt-1">Masukkan informasi detail untuk inventaris baru.</p>
</div>

<div class="max-w-2xl bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
    <form action="<?= base_url('items/create') ?>" method="POST" class="space-y-6">
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
            <input type="text" name="sku" id="sku" value="<?= old('sku') ?>" 
                class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                placeholder="Misal: BRG-001" required>
        </div>

        <div class="space-y-2">
            <label for="name" class="text-sm font-bold text-slate-700 ml-1">Nama Barang</label>
            <input type="text" name="name" id="name" value="<?= old('name') ?>"
                class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                placeholder="Masukkan nama barang..." required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="min_stock" class="text-sm font-bold text-slate-700 ml-1">Stok Minimum (Alert)</label>
                <input type="number" name="min_stock" id="min_stock" value="<?= old('min_stock', 5) ?>"
                    class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                    required>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-400 ml-1">Stok Awal</label>
                <input type="text" value="0" disabled
                    class="w-full px-5 py-3 bg-slate-100 border border-slate-100 rounded-2xl text-slate-400 cursor-not-allowed">
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                Simpan Barang & Generate QR
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
