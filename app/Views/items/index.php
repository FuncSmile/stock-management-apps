<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Daftar Barang<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="{ showModal: false, previewUrl: '', previewName: '' }">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Daftar Barang</h1>
            <p class="text-slate-500 mt-1">Kelola informasi barang dan generate QR Code.</p>
        </div>
        <?php if (auth()->user()?->inGroup('owner')): ?>
        <a href="<?= base_url('items/new') ?>" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-all hover:shadow-indigo-200/50 hover:-translate-y-0.5 shadow-lg shadow-indigo-100">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Barang
        </a>
        <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center text-emerald-700 animate-in fade-in slide-in-from-top-2 duration-300">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-bold"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <!-- Search Bar -->
    <div class="mb-6">
        <form action="<?= base_url('items') ?>" method="get" class="flex gap-2">
            <div class="relative flex-1 group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="<?= esc($search ?? '') ?>" 
                    class="block w-full pl-11 pr-12 py-3.5 bg-white border border-slate-100 rounded-2xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 shadow-sm transition-all" 
                    placeholder="Cari berdasarkan nama atau SKU...">
                
                <?php if (!empty($search)): ?>
                <a href="<?= base_url('items') ?>" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
                <?php endif; ?>
            </div>
            <button type="submit" class="px-6 py-3.5 bg-slate-900 text-white font-bold text-sm rounded-2xl hover:bg-slate-800 transition-all active:scale-95 shadow-lg shadow-slate-100">
                Cari
            </button>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition-all">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">SKU</th>
                        <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Informasi Barang</th>
                        <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">QR Code</th>
                        <?php if (auth()->user()?->inGroup('owner')): ?>
                        <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Modal</th>
                        <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Bandrol</th>
                        <?php endif; ?>
                        <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Stok</th>
                        <?php if (auth()->user()?->inGroup('owner')): ?>
                        <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($items as $item): ?>
                    <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50/50 border border-indigo-100 px-2.5 py-1.5 rounded-lg"><?= $item['sku'] ?></span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors"><?= $item['name'] ?></span>
                                <span class="text-[10px] text-slate-400 font-mono mt-0.5"><?= $item['id'] ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <?php if ($item['qr_code_path'] && file_exists(FCPATH . $item['qr_code_path'])): ?>
                                <button 
                                    @click="showModal = true; previewUrl = '<?= base_url($item['qr_code_path']) ?>'; previewName = '<?= $item['name'] ?>'"
                                    class="relative inline-block group/qr cursor-zoom-in"
                                >
                                    <img src="<?= base_url($item['qr_code_path']) ?>" alt="QR" class="w-12 h-12 rounded-xl border border-slate-200 p-1 bg-white shadow-sm group-hover/qr:shadow-md transition-all group-hover/qr:scale-105">
                                    <div class="absolute inset-0 bg-indigo-600/0 group-hover/qr:bg-indigo-600/10 rounded-xl transition-all flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600 opacity-0 group-hover/qr:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                    </div>
                                </button>
                            <?php else: ?>
                                <div class="w-12 h-12 bg-slate-50 rounded-xl border border-dashed border-slate-200 mx-auto flex items-center justify-center text-[8px] text-slate-400 uppercase font-bold tracking-tighter">No QR</div>
                            <?php endif; ?>
                        </td>
                        <?php if (auth()->user()?->inGroup('owner')): ?>
                        <td class="px-6 py-5 text-right whitespace-nowrap">
                            <span class="text-[10px] font-bold text-slate-400 uppercase mr-1">Rp</span>
                            <span class="text-sm font-bold text-slate-600"><?= number_format($item['base_price'], 0, ',', '.') ?></span>
                        </td>
                        <td class="px-6 py-5 text-right whitespace-nowrap">
                            <?php $isLoss = $item['mark_price'] < $item['base_price']; ?>
                            <div class="flex flex-col items-end">
                                <div class="<?= $isLoss ? 'text-rose-600' : 'text-indigo-600' ?>">
                                    <span class="text-[10px] font-bold <?= $isLoss ? 'text-rose-400' : 'text-indigo-400' ?> uppercase mr-1">Rp</span>
                                    <span class="text-sm font-bold"><?= number_format($item['mark_price'], 0, ',', '.') ?></span>
                                </div>
                                <?php if ($isLoss): ?>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[8px] font-black bg-rose-50 text-rose-600 border border-rose-100 uppercase tracking-tighter mt-1 animate-pulse">
                                        <svg class="w-2.5 h-2.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        Potensi Rugi!
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>
                        <td class="px-6 py-5 text-center">
                            <div class="inline-flex flex-col items-center">
                                 <span class="text-sm font-black <?= $item['current_stock'] <= $item['min_stock'] ? 'text-rose-600' : 'text-slate-700' ?>">
                                    <?= $item['current_stock'] ?>
                                </span>
                                <?php if ($item['current_stock'] <= $item['min_stock']): ?>
                                <span class="mt-1 px-2 py-0.5 rounded-full text-[8px] font-black bg-rose-100 text-rose-600 uppercase tracking-tighter border border-rose-200">Low Stock</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php if (auth()->user()?->inGroup('owner')): ?>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end space-x-1">
                                <a href="<?= base_url('items/edit/' . $item['id']) ?>" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <a href="<?= base_url('items/delete/' . $item['id']) ?>" 
                                   class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" 
                                   title="Hapus"
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="text-slate-400 font-medium">Belum ada data barang.</p>
                                <a href="<?= base_url('items/new') ?>" class="text-indigo-600 font-bold mt-2 hover:underline">Tambah barang pertama Anda</a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- QR Preview Modal -->
    <template x-teleport="body">
        <div 
            x-show="showModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            @click.self="showModal = false"
            x-cloak
        >
            <div 
                x-show="showModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="bg-white rounded-[2.5rem] p-8 max-w-sm w-full shadow-2xl overflow-hidden relative"
            >
                <div class="absolute top-6 right-6">
                    <button @click="showModal = false" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="text-center">
                    <h3 class="text-xl font-bold text-slate-900 mb-1" x-text="previewName"></h3>
                    <p class="text-slate-500 text-sm mb-8">Generated QR Code</p>
                    
                    <div class="bg-slate-50 rounded-[2rem] p-8 mb-8 flex items-center justify-center border border-slate-100 shadow-inner">
                        <img :src="previewUrl" alt="QR Preview" class="w-48 h-48 drop-shadow-xl">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <a :href="previewUrl" download class="flex items-center justify-center px-4 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download
                        </a>
                        <button @click="window.print()" class="flex items-center justify-center px-4 py-3 bg-slate-100 text-slate-700 font-bold rounded-2xl hover:bg-slate-200 transition-colors text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2v4a2 2 0 002 2h2m3 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
<?= $this->endSection() ?>
