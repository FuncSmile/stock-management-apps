<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Selamat Datang, Admin</h1>
        <p class="text-slate-500 mt-1">Berikut adalah ringkasan inventaris kamu hari ini.</p>
    </div>
    
    <!-- Period Selector -->
    <div class="flex p-1 bg-slate-100 rounded-xl self-start md:self-auto">
        <a href="?period=daily" 
           class="px-4 py-2 rounded-lg text-sm font-semibold transition-all <?= $current_period === 'daily' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' ?>">
            Harian
        </a>
        <a href="?period=monthly" 
           class="px-4 py-2 rounded-lg text-sm font-semibold transition-all <?= $current_period === 'monthly' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' ?>">
            Bulanan
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Revenue Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2.5 py-1 rounded-full uppercase tracking-widest"><?= $current_period === 'daily' ? 'Hari Ini' : 'Bulan Ini' ?></span>
        </div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Total Omzet</p>
        <p class="text-2xl font-black text-slate-900 mt-1">Rp <?= number_format($total_revenue, 0, ',', '.') ?></p>
    </div>

    <!-- Profit Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <span class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-2.5 py-1 rounded-full uppercase tracking-widest"><?= $current_period === 'daily' ? 'Hari Ini' : 'Bulan Ini' ?></span>
        </div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Estimasi Profit</p>
        <p class="text-2xl font-black text-indigo-600 mt-1">Rp <?= number_format($total_profit, 0, ',', '.') ?></p>
    </div>

    <!-- Low Stock -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <?php if ($low_stock_count > 0): ?>
                <span class="text-[10px] font-black text-rose-500 bg-rose-50 px-2.5 py-1 rounded-full uppercase tracking-widest">Penting</span>
            <?php endif; ?>
        </div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Stok Menipis</p>
        <p class="text-2xl font-black text-slate-900 mt-1"><?= number_format($low_stock_count) ?></p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-bold text-slate-500">Transaksi IN</span>
            <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-900"><?= number_format($total_in) ?></p>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-bold text-slate-500">Transaksi OUT</span>
            <div class="w-8 h-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-900"><?= number_format($total_out) ?></p>
    </div>
</div>

<!-- Recent Activity & Chart placeholder -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-8">
        <!-- Sales Movement Chart -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-6 font-heading flex items-center uppercase tracking-tight">
                <span class="w-2 h-6 bg-indigo-600 rounded-full mr-3"></span>
                Grafik Performa Penjualan
            </h3>
            <div class="h-64 relative">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Stock Movement Chart -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-6 font-heading flex items-center uppercase tracking-tight">
                <span class="w-2 h-6 bg-emerald-600 rounded-full mr-3"></span>
                Grafik Pergerakan Stok
            </h3>
            <div class="h-64 relative">
                <canvas id="movementChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 flex flex-col h-full">
        <h3 class="text-lg font-bold text-slate-900 mb-6 font-heading flex items-center uppercase tracking-tight">
            <span class="w-2 h-6 bg-slate-200 rounded-full mr-3"></span>
            Aktivitas Terakhir
        </h3>
        <div class="space-y-6 flex-1">
            <?php if (empty($recent_activity)): ?>
                <div class="text-center py-8">
                    <p class="text-slate-400 text-sm italic">Belum ada aktivitas.</p>
                </div>
            <?php else: ?>
                <?php foreach ($recent_activity as $activity): ?>
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center <?= $activity['type'] === 'IN' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' ?>">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <?php if ($activity['type'] === 'IN'): ?>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                <?php else: ?>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                <?php endif; ?>
                            </svg>
                        </div>
                        <div class="ml-4 overflow-hidden">
                            <p class="text-sm font-bold text-slate-800 truncate"><?= $activity['item_name'] ?></p>
                            <p class="text-[10px] text-slate-400 font-mono"><?= date('d M, H:i', strtotime($activity['created_at'])) ?> • <span class="font-black <?= $activity['type'] === 'IN' ? 'text-emerald-500' : 'text-rose-500' ?> tracking-tighter"><?= $activity['type'] === 'IN' ? '+' : '-' ?><?= $activity['quantity'] ?></span></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <a href="<?= base_url('audit') ?>" class="w-full mt-8 py-4 text-center text-xs font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 rounded-[2rem] hover:bg-indigo-100 transition-all active:scale-[0.98]">
            Lihat Laporan Audit
        </a>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('movementChart');
        if (!canvas) {
            console.warn("Element 'movementChart' not found, skipping chart init.");
            return;
        }

        const ctx = canvas.getContext('2d');
        
        // Gradient for IN
        const gradientIn = ctx.createLinearGradient(0, 0, 0, 400);
        gradientIn.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
        gradientIn.addColorStop(1, 'rgba(16, 185, 129, 0)');

        // Gradient for OUT
        const gradientOut = ctx.createLinearGradient(0, 0, 0, 400);
        gradientOut.addColorStop(0, 'rgba(244, 63, 94, 0.2)');
        gradientOut.addColorStop(1, 'rgba(244, 63, 94, 0)');

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: { family: "'Inter', sans-serif", size: 10, weight: '700' }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    padding: 12,
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 11 },
                    cornerRadius: 12
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: "'Inter', sans-serif", size: 10 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.03)', drawBorder: false },
                    ticks: { precision: 0, font: { family: "'Inter', sans-serif", size: 10 } }
                }
            }
        };

        // 1. Stock Movement Chart
        const movementChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($chart_labels ?? []) ?>,
                datasets: [
                    {
                        label: 'Stok Masuk',
                        data: <?= json_encode($chart_in ?? []) ?>,
                        borderColor: '#10b981',
                        backgroundColor: gradientIn,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#10b981',
                        pointRadius: 3
                    },
                    {
                        label: 'Stok Keluar',
                        data: <?= json_encode($chart_out ?? []) ?>,
                        borderColor: '#f43f5e',
                        backgroundColor: gradientOut,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#f43f5e',
                        pointRadius: 3
                    }
                ]
            },
            options: commonOptions
        });

        // 2. Sales Performance Chart
        const salesCanvas = document.getElementById('salesChart');
        if (salesCanvas) {
            const sCtx = salesCanvas.getContext('2d');
            
            const gradRev = sCtx.createLinearGradient(0, 0, 0, 400);
            gradRev.addColorStop(0, 'rgba(16, 185, 129, 0.1)');
            gradRev.addColorStop(1, 'rgba(16, 185, 129, 0)');

            const gradProf = sCtx.createLinearGradient(0, 0, 0, 400);
            gradProf.addColorStop(0, 'rgba(79, 70, 229, 0.1)');
            gradProf.addColorStop(1, 'rgba(79, 70, 229, 0)');

            new Chart(sCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($chart_labels ?? []) ?>,
                    datasets: [
                        {
                            label: 'Omzet (Revenue)',
                            data: <?= json_encode($sales_revenue ?? []) ?>,
                            borderColor: '#10b981',
                            backgroundColor: gradRev,
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#10b981'
                        },
                        {
                            label: 'Profit',
                            data: <?= json_encode($sales_profit ?? []) ?>,
                            borderColor: '#4f46e5',
                            backgroundColor: gradProf,
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#4f46e5'
                        }
                    ]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        ...commonOptions.scales,
                        y: {
                            ...commonOptions.scales.y,
                            ticks: {
                                ...commonOptions.scales.y.ticks,
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
