<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Audit Logs<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Audit Logs</h1>
        <p class="text-slate-500 mt-1">Riwayat perubahan manual dan aktivitas sistem sensitif.</p>
    </div>
    <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-center">
        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Owner Only Access</span>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Waktu</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Aktor</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Aksi</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">Detail Perubahan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($logs as $log): ?>
                    <?php $payload = json_decode($log['payload'], true); ?>
                    <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            <?= date('d M Y, H:i', strtotime($log['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-[10px] font-bold mr-3">
                                    <?= substr($userMap[$log['user_id']] ?? 'U', 0, 1) ?>
                                </div>
                                <span class="text-sm font-semibold text-slate-700"><?= $userMap[$log['user_id']] ?? 'Unknown' ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                <?= $log['action'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($payload): ?>
                                <div class="space-y-2">
                                    <?php if (isset($payload['old_stock']) && isset($payload['new_stock'])): ?>
                                        <div class="flex items-center space-x-3 text-sm">
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded font-mono"><?= $payload['old_stock'] ?></span>
                                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded font-bold font-mono"><?= $payload['new_stock'] ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($payload['reason'])): ?>
                                        <div class="flex items-start bg-amber-50 rounded-xl p-3 border border-amber-100">
                                            <svg class="w-4 h-4 text-amber-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                            <p class="text-xs text-amber-700 italic">"<?= $payload['reason'] ?>"</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <span class="text-xs text-slate-400 italic">Tidak ada payload detail.</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <p class="text-slate-400 font-medium">Belum ada log audit yang tersedia.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
