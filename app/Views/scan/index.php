<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Scanner QR Stok<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="scannerApp()" class="max-w-2xl mx-auto">
    <!-- Header & Mode Switcher -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Scanner QR</h1>
        <p class="text-slate-500 text-sm mb-4">Arahkan kamera ke QR Code barang untuk menambah hitungan secara otomatis.</p>
        
        <div class="flex p-1 bg-slate-100 rounded-xl">
            <button 
                @click="mode = 'IN'" 
                :class="mode === 'IN' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                class="flex-1 flex items-center justify-center py-2.5 px-4 rounded-lg font-semibold transition-all duration-200"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Stok Masuk
            </button>
            <button 
                @click="mode = 'OUT'" 
                :class="mode === 'OUT' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                class="flex-1 flex items-center justify-center py-2.5 px-4 rounded-lg font-semibold transition-all duration-200"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                Stok Keluar
            </button>
        </div>
    </div>

    <!-- Scanner Window -->
    <div class="relative bg-black rounded-3xl overflow-hidden shadow-2xl aspect-square mb-6 border-4" :class="mode === 'IN' ? 'border-emerald-500/20' : 'border-rose-500/20'">
        <div id="reader" class="w-full h-full"></div>
        
        <!-- Scanner Overlay -->
        <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center">
            <div class="w-64 h-64 border-2 border-dashed border-white/50 rounded-2xl relative">
                <div class="absolute inset-0 bg-white/5 animate-pulse rounded-2xl"></div>
                <!-- Shifting Scanning Line -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.8)] animate-[scan_2s_linear_infinite]"></div>
            </div>
            <p class="text-white/70 text-xs mt-8 bg-black/40 px-3 py-1.5 rounded-full backdrop-blur-sm">Mendeteksi QR Code secara kontinu...</p>
        </div>

        <!-- Success/Error Flash -->
        <div x-show="flashSuccess" x-transition.opacity class="absolute inset-0 bg-emerald-500/20 pointer-events-none"></div>
        <div x-show="flashError" x-transition.opacity class="absolute inset-0 bg-rose-500/20 pointer-events-none"></div>

        <!-- Hidden CSRF for Alpine -->
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token">
    </div>

    <!-- Scanned Items List -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Daftar Scan <span x-show="items.length > 0" class="ml-2 px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs rounded-full" x-text="items.length"></span></h3>
            <button @click="clearItems()" x-show="items.length > 0" class="text-xs text-rose-500 hover:text-rose-600 font-medium">Reset</button>
        </div>

        <div class="divide-y divide-slate-50 max-h-80 overflow-y-auto">
            <template x-for="(item, index) in items" :key="item.uid">
                <div 
                    class="px-6 py-4 flex items-center justify-between transition-colors duration-300"
                    :class="flashItemSku === item.sku ? (mode === 'IN' ? 'bg-emerald-50' : 'bg-rose-50') : ''"
                >
                    <div class="flex-1 min-w-0 pr-4">
                        <p class="text-sm font-bold text-slate-900 truncate" x-text="item.name || 'Memuat...'"></p>
                        <p class="text-xs text-slate-500 font-mono" x-text="item.sku"></p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center bg-slate-50 rounded-lg p-1 border border-slate-100">
                            <button @click="decrement(index)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path></svg>
                            </button>
                            <input type="number" x-model.number="item.qty" class="w-10 text-center bg-transparent border-none p-0 text-sm font-bold focus:ring-0 appearance-none">
                            <button @click="increment(index)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path></svg>
                            </button>
                        </div>
                        <button @click="removeItem(index)" class="text-slate-300 hover:text-rose-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            </template>

            <div x-show="items.length === 0" class="px-6 py-12 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 17h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <p class="text-slate-400 text-sm">Belum ada barang yang di-scan.</p>
            </div>
        </div>
    </div>

    <!-- Sync Button -->
    <div class="mt-8">
        <button 
            @click="submitBatch()" 
            :disabled="items.length === 0 || loading"
            class="w-full py-4 px-6 rounded-2xl shadow-lg shadow-indigo-100 flex items-center justify-center font-bold transition-all transform active:scale-[0.98]"
            :class="items.length === 0 ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-indigo-600 text-white hover:bg-indigo-700'"
        >
            <template x-if="!loading">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    <span>Simpan Batch Perubahan</span>
                </div>
            </template>
            <template x-if="loading">
                <div class="flex items-center">
                    <svg class="animate-spin h-5 w-5 mr-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Memproses...</span>
                </div>
            </template>
        </button>
    </div>
</div>

<style>
@keyframes scan {
    0% { top: 0%; opacity: 0; }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { top: 100%; opacity: 0; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function scannerApp() {
        return {
            mode: 'IN', // IN or OUT
            items: [],
            loading: false,
            flashSuccess: false,
            flashError: false,
            flashItemSku: null,
            scanCooldowns: {},
            lastScan: 0,

            init() {
                this.startScanner();
            },

            startScanner() {
                const config = { 
                    fps: 10, 
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                };
                
                const scanner = new Html5Qrcode("reader");
                scanner.start(
                    { facingMode: "environment" }, 
                    config, 
                    (decodedText) => this.onScanSuccess(decodedText)
                ).catch(err => {
                    console.error("Scanner error:", err);
                    alert("Kamera tidak dapat diakses. Pastikan Anda menggunakan HTTPS.");
                });
            },

            async onScanSuccess(decodedText) {
                console.log("Scan detected:", decodedText);
                const now = Date.now();
                
                // Cooldown: prevent same SKU within 1.5 seconds
                if (this.scanCooldowns[decodedText] && now - this.scanCooldowns[decodedText] < 1500) {
                    return;
                }

                this.scanCooldowns[decodedText] = now;
                
                // Audio/Haptic Feedback
                this.playFeedback();

                // Search by either the scanned text or the mapped SKU
                const existingIndex = this.items.findIndex(i => i.originalScan === decodedText || i.sku === decodedText);
                
                if (existingIndex > -1) {
                    this.items[existingIndex].qty++;
                    this.triggerFlashItem(this.items[existingIndex].sku);
                } else {
                    // New item
                    const newItem = {
                        uid: now, // stable key
                        originalScan: decodedText,
                        sku: decodedText,
                        name: null,
                        qty: 1
                    };
                    this.items.unshift(newItem);
                    this.triggerFlashItem(decodedText);
                    
                    // Fetch background info
                    this.fetchItemInfo(decodedText, newItem.uid);
                }
            },

            async fetchItemInfo(search, uid) {
                try {
                    // Use relative path to avoid CORS/Mixed Content issues
                    const response = await fetch(`${window.location.origin}/api/items/info/${search}`);
                    const result = await response.json();
                    
                    const index = this.items.findIndex(i => i.uid === uid);
                    if (index > -1) {
                        if (result.status === 'success') {
                            this.items[index].name = result.data.name;
                            this.items[index].sku = result.data.sku; // Update to actual SKU
                        } else {
                            // Item not found in DB
                            this.items[index].name = "Barang tidak terdaftar";
                            this.flashError = true;
                            setTimeout(() => this.flashError = false, 300);
                        }
                    }
                } catch (error) {
                    console.error("Fetch info error:", error);
                    const index = this.items.findIndex(i => i.uid === uid);
                    if (index > -1) {
                        this.items[index].name = search; // Fallback to search text
                    }
                }
            },

            playFeedback() {
                // Audio Beep - ensure it doesn't crash the flow
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    if (ctx.state === 'suspended') {
                        ctx.resume();
                    }
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    gain.gain.setValueAtTime(0.1, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.1);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.1);
                } catch (e) {
                    console.log("Audio feedback suppressed or failed");
                }

                // Vibration
                if (navigator.vibrate) {
                    navigator.vibrate(100);
                }

                // Global Visual Flash
                this.flashSuccess = true;
                setTimeout(() => this.flashSuccess = false, 200);
            },

            triggerFlashItem(sku) {
                this.flashItemSku = sku;
                setTimeout(() => {
                    if (this.flashItemSku === sku) this.flashItemSku = null;
                }, 500);
            },

            increment(index) {
                this.items[index].qty++;
            },

            decrement(index) {
                if (this.items[index].qty > 1) {
                    this.items[index].qty--;
                }
            },

            removeItem(index) {
                this.items.splice(index, 1);
            },

            clearItems() {
                if (confirm('Bersihkan semua daftar scan?')) {
                    this.items = [];
                }
            },

            async submitBatch() {
                this.loading = true;
                const csrfToken = document.getElementById('csrf_token').name;
                const csrfHash = document.getElementById('csrf_token').value;
                
                try {
                    const response = await fetch(`${window.location.origin}/api/stock/batch-update`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfHash
                        },
                        body: JSON.stringify({
                            type: this.mode,
                            items: this.items
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.status === 'success') {
                        alert(`Berhasil! ${result.message}`);
                        this.items = [];
                    } else {
                        alert(`Gagal: ${result.message}`);
                    }
                } catch (error) {
                    console.error("Submit error:", error);
                    alert("Terjadi kesalahan saat menyimpan data.");
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
<?= $this->endSection() ?>
