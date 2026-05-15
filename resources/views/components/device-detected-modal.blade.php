{{-- DeviceDetectedModal (Full Debug Mode) --}}
<div x-data="deviceModal()" x-init="init()" x-show="$store.deviceModal.open" x-cloak
    class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm flex justify-center items-center z-[60] p-4">
    <div class="p-6 rounded-[2.5rem] shadow-2xl w-full max-w-sm border bg-slate-900 border-white/10 text-white flex flex-col">

        {{-- Header --}}
        <div class="text-center mb-6">
            <div class="mx-auto w-14 h-14 rounded-full flex items-center justify-center mb-3 animate-pulse bg-red-500/20 text-red-500">
                <i data-lucide="shield-alert" class="w-[30px] h-[30px]"></i>
            </div>
            <h3 class="text-xl font-black uppercase tracking-tight leading-tight">
                Perangkat Baru Terdeteksi
            </h3>
            <p class="text-[10px] opacity-60 font-bold uppercase mt-1">
                Keamanan Akun AnoPos
            </p>
        </div>

        {{-- Info Box --}}
        <div class="rounded-3xl p-4 mb-4 text-[11px] space-y-3 bg-slate-800/50 border border-white/5">
            <div class="flex items-start gap-3">
                <i data-lucide="monitor-smartphone" class="text-blue-500 w-4 h-4 mt-0.5"></i>
                <div>
                    <p class="font-bold opacity-50 uppercase text-[9px]">Detail Perangkat</p>
                    <p class="font-mono" x-text="deviceInfo.os + ' • ' + deviceInfo.browser"></p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <i data-lucide="map-pin" class="text-emerald-500 w-4 h-4 mt-0.5"></i>
                <div>
                    <p class="font-bold opacity-50 uppercase text-[9px]">Estimasi Lokasi</p>
                    <p class="font-mono" x-text="locationText"></p>
                </div>
            </div>
        </div>

        {{-- Device Name --}}
        <div class="mb-6">
            <label class="text-[10px] font-black uppercase ml-2 mb-1 block opacity-70">
                Nama Perangkat Anda
            </label>
            <input type="text" x-model="deviceName" placeholder="Contoh: Laptop Kasir Depan"
                class="w-full px-4 py-3 rounded-2xl text-xs font-bold outline-none border bg-slate-800 border-white/10 focus:border-amber-500/50 text-white transition-all" />
            <p class="text-[9px] mt-2 italic px-2 opacity-60">
                * Nama unik untuk approval admin
            </p>
        </div>

        {{-- Buttons --}}
        <div class="grid grid-cols-2 gap-3">
            <button @click="reDetect" :disabled="isProcessing"
                class="flex items-center justify-center gap-2 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest border border-white/10 hover:bg-white/5 text-gray-400 disabled:opacity-50 transition-all">
                <i data-lucide="refresh-cw" :class="isProcessing ? 'animate-spin' : ''" class="w-3.5 h-3.5"></i>
                Re-Detect
            </button>

            <button @click="requestTrust" :disabled="!deviceName || isProcessing"
                class="flex items-center justify-center gap-2 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest bg-amber-600 text-white hover:bg-amber-500 disabled:opacity-30 transition-all">
                <template x-if="isProcessing">
                    <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                </template>
                <template x-if="!isProcessing">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                </template>
                <span x-text="isProcessing ? 'Processing...' : 'Request Trust'"></span>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        // Mencegah redeklarasi jika store sudah ada
        if (!Alpine.store('deviceModal')) {
            Alpine.store('deviceModal', { open: false });
        }
    });

    function deviceModal() {
        return {
            deviceName: '',
            isProcessing: false,
            deviceInfo: { os: 'Detecting...', browser: 'Detecting...' },
            location: { lat: null, lon: null, error: 'Detecting...' },

            init() {
                console.log('[DEBUG] Modal Component Initialized');
                this.detectDevice();
                this.$watch('$store.deviceModal.open', (val) => {
                    if (val) {
                        console.log('[DEBUG] Modal Dibuka');
                        this.detectDevice();
                        this.safeLucide();
                    }
                });
            },

            // SAFE NOTIF WRAPPER
            notify(type, title, message) {
                console.log(`[NOTIF ${type.toUpperCase()}] ${title}: ${message}`);
                try {
                    if(this.$store && this.$store.notif) {
                        this.$store.notif.set(type, title, message);
                    } else {
                        alert(`${title}\n${message}`);
                    }
                } catch(e) {
                    console.error('[DEBUG] Gagal memanggil notifikasi Alpine', e);
                    alert(`${title}\n${message}`);
                }
            },

            // HELPER: Load Script dengan Fallback
            loadFpScript() {
                return new Promise((resolve, reject) => {
                    console.log('[DEBUG] Memeriksa status FingerprintJS...');

                    if (window.FingerprintJS) {
                        console.log('[DEBUG] FingerprintJS sudah tersedia di window.');
                        return resolve(window.FingerprintJS);
                    }

                    console.log('[DEBUG] Menyuntikkan script FingerprintJS...');
                    const script = document.createElement('script');
                    // Menggunakan unpkg sebagai CDN alternatif yang lebih jarang diblokir adblocker
                    script.src = "https://unpkg.com/@fingerprintjs/fingerprintjs@4/dist/fp.min.js";
                    script.async = true;

                    script.onload = () => {
                        console.log('[DEBUG] Script FingerprintJS berhasil dimuat!');
                        resolve(window.FingerprintJS || window.fpPromise);
                    };

                    script.onerror = (e) => {
                        console.error('[DEBUG] FATAL ERROR: Script gagal dimuat. Apakah Adblocker menyala?', e);
                        reject(new Error('Gagal memuat sistem keamanan. Harap matikan AdBlock Anda.'));
                    };

                    document.head.appendChild(script);
                });
            },

            detectDevice() {
                const ua = navigator.userAgent;
                let browser = 'Unknown', os = 'Unknown';
                if (ua.includes('Firefox')) browser = 'Firefox';
                else if (ua.includes('Chrome')) browser = 'Chrome';
                else if (ua.includes('Safari')) browser = 'Safari';

                if (ua.includes('Win')) os = 'Windows';
                else if (ua.includes('Mac')) os = 'MacOS';
                else if (ua.includes('Android')) os = 'Android';
                else if (ua.includes('iPhone')) os = 'iOS';
                else if (ua.includes('Linux')) os = 'Linux';

                this.deviceInfo = { os, browser };

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (pos) => { this.location = { lat: pos.coords.latitude, lon: pos.coords.longitude, error: null } },
                        () => { this.location.error = 'Akses lokasi ditolak' }
                    )
                }
            },

            get locationText() {
                return this.location.error ? this.location.error : `${this.location.lat}, ${this.location.lon}`;
            },

            async requestTrust() {
                console.log('[DEBUG] Tombol Request Trust ditekan');
                if (!this.deviceName) {
                    console.warn('[DEBUG] Nama perangkat kosong, membatalkan request.');
                    return;
                }

                if (this.isProcessing) {
                    console.warn('[DEBUG] Proses masih berjalan, mencegah double-click.');
                    return;
                }

                this.isProcessing = true;

                try {
                    console.group('[DEBUG] Menyiapkan Fingerprint');
                    const FPJS = await this.loadFpScript();
                    const fp = await FPJS.load();
                    const result = await fp.get();
                    const visitorId = result.visitorId;
                    console.log('[DEBUG] Visitor ID:', visitorId);
                    console.groupEnd();

                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfMeta) {
                        throw new Error("CSRF Token tidak ditemukan di tag <head>");
                    }

                    const payload = {
                        deviceName: this.deviceName,
                        os: this.deviceInfo.os,
                        browser: this.deviceInfo.browser,
                        location: this.location,
                        deviceFingerprint: visitorId,
                        timestamp: new Date().toISOString(),
                    };

                    console.log('[DEBUG] Payload yang akan dikirim:', payload);

                    const res = await fetch('/login/request-trust', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfMeta.content
                        },
                        body: JSON.stringify(payload)
                    });

                    console.log('[DEBUG] HTTP Status Response:', res.status);
                    const data = await res.json();
                    console.log('[DEBUG] Response Body:', data);

                    if (!res.ok) throw new Error(data.message || data.code || 'Terjadi kesalahan pada server');

                    this.notify('success', 'Berhasil', 'Device dalam proses verifikasi');
                    setTimeout(() => {
                        if(this.$store && this.$store.deviceModal) this.$store.deviceModal.open = false;
                    }, 1000);

                } catch (e) {
                    console.error('[DEBUG] EXCEPTION CATCHED:', e);
                    this.notify('error', 'Gagal', e.message || 'Request gagal dieksekusi');
                } finally {
                    console.log('[DEBUG] Mengembalikan status tombol menjadi aktif');
                    this.isProcessing = false;
                    this.safeLucide();
                }
            },

            async reDetect() {
                console.log('[DEBUG] Tombol Re-Detect ditekan');
                if (this.isProcessing) return;
                this.isProcessing = true;

                try {
                    const FPJS = await this.loadFpScript();
                    const fp = await FPJS.load();
                    const { visitorId } = await fp.get();

                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');

                    console.log('[DEBUG] Mengirim request re-verify...');
                    const res = await fetch('/login/re-verify-device', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : ''
                        },
                        body: JSON.stringify({ deviceFingerprint: visitorId })
                    });

                    console.log('[DEBUG] HTTP Status Re-Detect:', res.status);

                    if (res.ok) {
                        console.log('[DEBUG] Re-Detect berhasil, mengarahkan ulang...');
                        window.location.href = '/login/restaurant-pin';
                    } else {
                        const data = await res.json();
                        console.error('[DEBUG] Re-Detect gagal:', data);
                        this.notify('error', 'Gagal', data.message || 'Device belum terdaftar');
                    }
                } catch (e) {
                    console.error('[DEBUG] EXCEPTION Re-Detect:', e);
                    this.notify('error', 'Error', e.message || 'Gagal memindai perangkat');
                } finally {
                    this.isProcessing = false;
                    this.safeLucide();
                }
            },

            safeLucide() {
                setTimeout(() => {
                    try {
                        if (window.lucide) lucide.createIcons();
                    } catch(e) {}
                }, 50);
            }
        }
    }
</script>
