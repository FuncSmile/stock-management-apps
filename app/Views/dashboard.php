<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-900">Selamat Datang, Admin</h1>
    <p class="text-slate-500 mt-1">Berikut adalah ringkasan inventaris kamu hari ini.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <span class="text-xs font-bold text-green-500 bg-green-50 px-2 py-1 rounded-lg">+12%</span>
        </div>
        <p class="text-sm font-medium text-slate-500">Total Barang</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">1,284</p>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <span class="text-xs font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded-lg">Penting</span>
        </div>
        <p class="text-sm font-medium text-slate-500">Stok Menipis</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">12</p>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
            </div>
            <span class="text-xs font-bold text-indigo-500 bg-indigo-50 px-2 py-1 rounded-lg">Bulan Ini</span>
        </div>
        <p class="text-sm font-medium text-slate-500">Transaksi IN</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">450</p>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </div>
            <span class="text-xs font-bold text-amber-500 bg-amber-50 px-2 py-1 rounded-lg">Bulan Ini</span>
        </div>
        <p class="text-sm font-medium text-slate-500">Transaksi OUT</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">320</p>
    </div>
</div>

<!-- Recent Activity & Chart placeholder -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-6 font-heading">Grafik Pergerakan Stok</h3>
        <div class="h-64 bg-slate-50 rounded-xl flex items-center justify-center border-2 border-dashed border-slate-200">
            <p class="text-slate-400">Chart Pergerakan Stok akan muncul di sini</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-6 font-heading">Aktivitas Terakhir</h3>
        <div class="space-y-6">
            <div class="flex items-start">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex-shrink-0 flex items-center justify-center text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-slate-900">Stok Masuk: Kertas A4</p>
                    <p class="text-xs text-slate-500">2 jam yang lalu • +10 Rim</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="w-8 h-8 rounded-full bg-rose-100 flex-shrink-0 flex items-center justify-center text-rose-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-slate-900">Stok Keluar: Tinta Epson</p>
                    <p class="text-xs text-slate-500">5 jam yang lalu • -2 Pcs</p>
                </div>
            </div>
        </div>
        <button class="w-full mt-8 py-3 text-sm font-semibold text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
            Lihat Semua Aktivitas
        </button>
    </div>
</div>
<?= $this->endSection() ?>
