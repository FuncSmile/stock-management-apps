<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | NexStock QR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden">
    
    <!-- Animated Gradients Backdrop -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-100 rounded-full blur-[120px] opacity-60"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-emerald-50 rounded-full blur-[120px] opacity-60"></div>

    <div class="w-full max-w-md z-10">
        <!-- Logo Section -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-tr from-indigo-600 to-indigo-500 rounded-[2.5rem] shadow-2xl shadow-indigo-200 mb-6 transform hover:scale-105 transition-transform duration-300">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight">NexStock <span class="text-indigo-600">QR</span></h1>
            <p class="text-slate-500 mt-2 font-medium">Internal Inventory Management System</p>
        </div>

        <!-- Login Card -->
        <div class="glass rounded-[2rem] border border-white shadow-2xl overflow-hidden">
            <div class="p-8 sm:p-10">
                
                <?php if (session('error') !== null) : ?>
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start animate-in fade-in slide-in-from-top-4 duration-300">
                        <svg class="w-5 h-5 text-rose-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-bold text-rose-700"><?= session('error') ?></p>
                    </div>
                <?php elseif (session('errors') !== null) : ?>
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl animate-in fade-in slide-in-from-top-4 duration-300">
                        <div class="flex items-start mb-2">
                            <svg class="w-5 h-5 text-rose-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm font-bold text-rose-700">Mohon periksa inputan Anda:</p>
                        </div>
                        <ul class="text-xs text-rose-600 list-disc list-inside ml-8 space-y-1">
                            <?php foreach (session('errors') as $error) : ?>
                                <li><?= $error ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif ?>

                <?php if (session('message') !== null) : ?>
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-start animate-in fade-in slide-in-from-top-4 duration-300">
                        <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-bold text-emerald-700"><?= session('message') ?></p>
                    </div>
                <?php endif ?>

                <form action="<?= url_to('login') ?>" method="post" class="space-y-6">
                    <?= csrf_field() ?>

                    <!-- Username Field -->
                    <div class="space-y-2">
                        <label for="username" class="block text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Username / ID Karyawan</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input type="text" name="username" id="username" value="<?= old('username') ?>" 
                                class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 transition-all font-medium" 
                                placeholder="Masukkan username" required autofocus>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input type="password" name="password" id="password" 
                                class="block w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 transition-all font-medium" 
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <?php if (setting('Auth.allowRemembering')): ?>
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-50 transition-all pointer-custom shadow-sm" <?php if (old('remember')) : ?> checked <?php endif ?>>
                        <label for="remember" class="ml-3 text-sm font-semibold text-slate-600 cursor-pointer select-none">Ingat saya di perangkat ini</label>
                    </div>
                    <?php endif ?>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full py-5 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-[1.5rem] font-bold text-lg shadow-xl shadow-indigo-100 hover:shadow-indigo-200 active:scale-[0.98] transform transition-all duration-200 flex items-center justify-center">
                        <span>Masuk Sekarang</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                </form>

            </div>
            
            <!-- Bottom Footer -->
            <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-50 text-center">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Akses terbatas untuk personil Toko Sepatu NexStock</p>
            </div>
        </div>

        <p class="mt-8 text-center text-xs text-slate-400 font-medium tracking-tight whitespace-nowrap">
            &copy; 2026 StockSystem &bull; Crafted with <span class="text-rose-400 inline-block animate-pulse">❤</span>
        </p>
    </div>

</body>
</html>
