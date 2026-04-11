<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> | StockSystem</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900" x-data="{ sidebarOpen: false }">

    <!-- Mobile Header -->
    <header class="block lg:hidden bg-white border-b border-slate-200 px-4 py-3 sticky top-0 z-30">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="font-bold text-lg tracking-tight font-heading">StockSystem</span>
            </div>
            <button @click="sidebarOpen = true" class="p-2 text-slate-500 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>
    </header>

    <div class="flex min-h-screen">
        <!-- Sidebar / Navigation -->
        <aside 
            class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="px-6 py-8 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                            <svg width="24" height="24" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <span class="font-bold text-xl tracking-tight font-heading">StockSystem</span>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden p-1 text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 px-4 space-y-1">
                    <?php if (auth()->user()?->inGroup('owner')): ?>
                    <a href="<?= base_url('dashboard') ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors <?= current_url() == base_url('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' ?>">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <?php endif; ?>
                    <a href="<?= base_url('items') ?>" class="flex items-center px-4 py-3 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        Daftar Barang
                    </a>
                    <a href="<?= base_url('scan') ?>" class="flex items-center px-4 py-3 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 17h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Scan QR
                    </a>
                    <?php if (auth()->user()?->inGroup('owner')): ?>
                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Laporan</p>
                    </div>
                    <a href="<?= base_url('transactions') ?>" class="flex items-center px-4 py-3 text-sm font-medium text-slate-600 rounded-xl hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Transaksi Global
                    </a>
                    <?php endif; ?>
                </nav>

                <div class="p-4 border-t border-slate-100">
                    <div class="bg-slate-50 rounded-2xl p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold uppercase">
                                <?= substr(auth()->user()?->username ?? 'U', 0, 2) ?>
                            </div>
                             <div class="flex-1">
                                <p class="text-sm font-bold text-slate-900 leading-none"><?= auth()->user()?->username ?? 'User' ?></p>
                                <div class="mt-1 flex flex-wrap gap-1">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold <?= auth()->user()?->inGroup('owner') ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' ?> uppercase">
                                        <?= auth()->user()?->inGroup('owner') ? 'Owner' : 'Staff' ?>
                                    </span>
                                    <a href="<?= base_url('logout') ?>" class="text-[10px] text-rose-500 hover:text-rose-600 font-bold uppercase tracking-wider flex items-center">
                                        <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-w-0 flex flex-col">
            <div class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-slate-100 py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
                    <p class="text-sm text-slate-400">
                        &copy; <?= date('Y') ?> <span class="font-semibold text-indigo-600">StockSystem</span>. Crafted with <span class="text-rose-500">&hearts;</span>
                    </p>
                </div>
            </footer>
        </main>
    </div>

    <!-- Mobile Overlay -->
    <div 
        x-show="sidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="transition-opacity ease-linear duration-300" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false" 
        class="fixed inset-0 bg-slate-900/50 z-30 lg:hidden"
    ></div>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
