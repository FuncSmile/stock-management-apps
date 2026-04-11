<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Scanner QR Stok<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="scannerApp()" class="max-w-2xl mx-auto">
    <!-- Header & Mode Switcher -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Scanner QR</h1>
        <p class="text-slate-500 text-sm mb-4">Arahkan kamera ke QR Code barang untuk menambah hitungan secara otomatis.</p>
        
        <div class="flex p-1 bg-slate-100/80 backdrop-blur-md rounded-2xl mb-4 overflow-x-auto no-scrollbar shadow-inner">
            <button 
                @click="mode = 'SALE'" 
                :class="mode === 'SALE' ? 'bg-white text-indigo-600 shadow-md scale-[1.02]' : 'text-slate-500 hover:text-slate-700'"
                class="flex-1 flex items-center justify-center py-3.5 px-3 rounded-xl font-bold text-[11px] uppercase tracking-wider transition-all duration-300 min-w-fit"
            >
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Jual
            </button>
            <button 
                @click="mode = 'IN'" 
                :class="mode === 'IN' ? 'bg-white text-emerald-600 shadow-md scale-[1.02]' : 'text-slate-500 hover:text-slate-700'"
                class="flex-1 flex items-center justify-center py-3.5 px-3 rounded-xl font-bold text-[11px] uppercase tracking-wider transition-all duration-300 min-w-fit"
            >
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Masuk
            </button>
            <button 
                @click="mode = 'OUT'" 
                :class="mode === 'OUT' ? 'bg-white text-rose-600 shadow-md scale-[1.02]' : 'text-slate-500 hover:text-slate-700'"
                class="flex-1 flex items-center justify-center py-3.5 px-3 rounded-xl font-bold text-[11px] uppercase tracking-wider transition-all duration-300 min-w-fit"
            >
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                Keluar
            </button>
        </div>
    </div>

    <!-- Scanner Window -->
    <div class="relative bg-black rounded-[2.5rem] overflow-hidden shadow-2xl aspect-square mb-6 border-4 transition-all duration-500 ring-8 ring-slate-50" :class="{ 'border-indigo-500/30': mode === 'SALE', 'border-emerald-500/30': mode === 'IN', 'border-rose-500/30': mode === 'OUT' }">
        <div id="reader" class="w-full h-full"></div>
        
        <!-- Scanner Overlay -->
        <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center">
            <div class="w-3/4 h-3/4 border-2 border-dashed border-white/30 rounded-3xl relative">
                <div class="absolute inset-0 bg-white/5 animate-pulse rounded-3xl"></div>
                <!-- Shifting Scanning Line -->
                <div class="absolute top-0 left-0 right-0 h-0.5 bg-white shadow-[0_0_15px_#fff] animate-[scan_2.5s_linear_infinite]"></div>
            </div>
            <p class="text-white/50 text-[10px] mt-6 bg-black/40 px-4 py-2 rounded-full backdrop-blur-md uppercase tracking-[0.2em]">Ready to Scan</p>
        </div>

        <!-- Success/Error Flash -->
        <div x-show="flashSuccess" x-transition.opacity class="absolute inset-0 bg-emerald-500/20 pointer-events-none"></div>
        <div x-show="flashError" x-transition.opacity class="absolute inset-0 bg-rose-500/20 pointer-events-none"></div>

        <!-- Hidden CSRF for Alpine -->
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token">
    </div>

    <!-- Scanned Items List -->
    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden mb-24">
        <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center">
                Daftar Scan 
                <span x-show="items.length > 0" class="ml-2 px-2.5 py-1 bg-indigo-600 text-white text-[10px] rounded-full animate-bounce" x-text="items.length"></span>
            </h3>
            <button @click="clearItems()" x-show="items.length > 0" class="text-[10px] text-rose-500 hover:text-rose-600 font-bold uppercase tracking-widest">Reset</button>
        </div>

        <div class="divide-y divide-slate-50 max-h-[50vh] overflow-y-auto overscroll-contain">
            <template x-for="(item, index) in items" :key="item.uid">
                <div 
                    class="px-5 py-4 flex flex-col space-y-3 transition-colors duration-300"
                    :class="flashItemSku === item.sku ? (mode === 'IN' ? 'bg-emerald-50' : 'bg-rose-50') : ''"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0 pr-4">
                            <p class="text-sm font-black text-slate-900 leading-tight" x-text="item.name || 'Memuat...'"></p>
                            <p class="text-[10px] text-slate-400 font-mono tracking-tighter uppercase" x-text="item.sku"></p>
                        </div>
                        <button @click="removeItem(index)" class="p-2 text-slate-300 hover:text-rose-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <!-- Negotiated Price Input (SALE mode only) -->
                        <div x-show="mode === 'SALE'" class="flex-1 max-w-[140px]">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400">Rp</span>
                                <input type="number" x-model.number="item.deal_price" 
                                    class="w-full pl-8 pr-3 py-2.5 bg-slate-100/50 border-none rounded-xl text-xs font-black text-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            </div>
                        </div>

                        <div class="flex items-center bg-slate-100/80 rounded-xl p-1.5 border border-slate-200/50">
                            <button @click="decrement(index)" class="w-8 h-8 flex items-center justify-center text-slate-500 active:bg-white active:shadow-sm rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path></svg>
                            </button>
                            <input type="number" x-model.number="item.qty" class="w-10 text-center bg-transparent border-none p-0 text-sm font-black text-slate-800 focus:ring-0 appearance-none">
                            <button @click="increment(index)" class="w-8 h-8 flex items-center justify-center text-slate-500 active:bg-white active:shadow-sm rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="items.length === 0" class="px-6 py-16 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-4 text-slate-200 border-2 border-dashed border-slate-100">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Scan barang untuk memuliai</p>
            </div>
        </div>
    </div>

    <!-- Floating Sync Button -->
    <div class="fixed bottom-6 left-6 right-6 lg:relative lg:bottom-0 lg:left-0 lg:right-0 lg:mt-8 z-20">
        <button 
            @click="submitBatch()" 
            :disabled="items.length === 0 || loading"
            class="w-full py-5 px-6 rounded-3xl shadow-2xl shadow-indigo-200 flex items-center justify-center font-black uppercase tracking-widest transition-all transform active:scale-[0.95]"
            :class="items.length === 0 ? 'bg-slate-200 text-slate-400 cursor-not-allowed shadow-none' : 'bg-indigo-600 text-white hover:bg-indigo-700'"
        >
            <template x-if="!loading">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-text="mode === 'SALE' ? 'Selesaikan Nota' : 'Simpan Batch'"></span>
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
    0% { top: 10%; opacity: 0; }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { top: 90%; opacity: 0; }
}
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function scannerApp() {
        return {
            mode: '<?= auth()->user()->inGroup('owner') ? 'IN' : 'SALE' ?>', 
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
                        qty: 1,
                        deal_price: 0
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
                            this.items[index].deal_price = result.data.mark_price; // Default to mark price
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
                    const endpoint = this.mode === 'SALE' ? '/api/sales/process' : '/api/stock/batch-update';
                    const response = await fetch(`${window.location.origin}${endpoint}`, {
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
                        if (this.mode === 'SALE') {
                            // Redirect to receipt page immediately
                            window.location.href = `${window.location.origin}/sales/receipt/${result.batch_id}`;
                        } else {
                            alert(`Berhasil! ${result.message}`);
                            this.items = [];
                        }
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
