{{-- Pengganti: DeviceDetectedModal.tsx --}}
<div
    x-data="deviceModal()"
    x-show="$store.deviceModal.open"
    x-cloak
    class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm flex justify-center items-center z-[60] p-4"
>
    <div class="p-6 rounded-[2.5rem] shadow-2xl w-full max-w-sm border bg-slate-900 border-white/10 text-white flex flex-col">

        {{-- Header --}}
        <div class="text-center mb-6">
            <div class="mx-auto w-14 h-14 rounded-full flex items-center justify-center mb-3 animate-pulse bg-red-500/20 text-red-500">
                <i data-lucide="shield-alert" class="w-[30px] h-[30px]"></i>
            </div>
            <h3 class="text-xl font-black uppercase tracking-tight leading-tight">Perangkat Baru Terdeteksi</h3>
            <p class="text-[10px] opacity-60 font-bold uppercase mt-1">Keamanan Akun AnoPos</p>
        </div>

        {{-- Info Box --}}
        <div class="rounded-3xl p-4 mb-4 text-[11px] space-y-3 bg-slate-800/50 border border-white/5">
            <div class="flex items-start gap-3">
                <i data-lucide="monitor-smartphone" class="text-blue-500 shrink-0 w-4 h-4 mt-0.5"></i>
                <div>
                    <p class="font-bold opacity-50 uppercase text-[9px]">Detail Perangkat</p>
                    <p class="font-mono" x-text="deviceInfo.os + ' • ' + deviceInfo.browser"></p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <i data-lucide="map-pin" class="text-emerald-500 shrink-0 w-4 h-4 mt-0.5"></i>
                <div>
                    <p class="font-bold opacity-50 uppercase text-[9px]">Estimasi Lokasi</p>
                    <p class="font-mono" x-text="locationText"></p>
                </div>
            </div>
        </div>

        {{-- Device Name Input --}}
        <div class="mb-6">
            <label class="text-[10px] font-black uppercase ml-2 mb-1 block opacity-70">Nama Perangkat Anda</label>
            <input
                type="text"
                x-model="deviceName"
                placeholder="Contoh: Laptop Kasir Depan"
                class="w-full px-4 py-3 rounded-2xl text-xs font-bold outline-none border bg-slate-800 border-white/10 focus:border-amber-500/50 text-white transition-all"
            />
            <p class="text-[9px] mt-2 italic px-2 opacity-60">* Berikan nama unik agar Admin dapat menyetujui akses perangkat ini.</p>
        </div>

        {{-- Buttons --}}
        <div class="grid grid-cols-2 gap-3">
            <button
                @click="reDetect"
                class="flex items-center justify-center gap-2 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest border border-white/10 hover:bg-white/5 text-gray-400 transition-all"
            >
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Re-Detect
            </button>
            <button
                @click="requestTrust"
                :disabled="!deviceName"
                class="flex items-center justify-center gap-2 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest bg-amber-600 text-white hover:bg-amber-500 disabled:opacity-30 transition-all"
            >
                <i data-lucide="send" class="w-3.5 h-3.5"></i> Request Trust
            </button>
        </div>
    </div>
</div>

<script>
// Alpine Store untuk kontrol modal — pengganti useState(false) di Login.tsx
document.addEventListener('alpine:init', () => {
    Alpine.store('deviceModal', { open: false })
})

function deviceModal() {
    return {
        deviceName: '',
        deviceInfo: { os: 'Detecting...', browser: 'Detecting...' },
        location: { lat: null, lon: null, error: 'Detecting...' },

        get locationText() {
            return this.location.error
                ? this.location.error
                : `${this.location.lat}, ${this.location.lon}`
        },

        init() {
            this.$watch('$store.deviceModal.open', (val) => {
                if (val) {
                    this.detectDevice()
                    lucide.createIcons()
                }
            })
        },

        detectDevice() {
            const ua = navigator.userAgent
            let browser = 'Unknown Browser', os = 'Unknown OS'

            if (ua.includes('Firefox')) browser = 'Mozilla Firefox'
            else if (ua.includes('Chrome')) browser = 'Google Chrome'
            else if (ua.includes('Safari')) browser = 'Apple Safari'

            if (ua.includes('Win')) os = 'Windows'
            else if (ua.includes('Mac')) os = 'MacOS'
            else if (ua.includes('Android')) os = 'Android'
            else if (ua.includes('iPhone')) os = 'iOS'
            else if (ua.includes('Linux')) os = 'Linux'

            this.deviceInfo = { os, browser }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        this.location = { lat: pos.coords.latitude, lon: pos.coords.longitude, error: null }
                    },
                    () => {
                        this.location.error = 'Akses lokasi ditolak'
                    }
                )
            }
        },

        buildPayload() {
            return {
                deviceName: this.deviceName,
                os: this.deviceInfo.os,
                browser: this.deviceInfo.browser,
                location: this.location,
                timestamp: new Date().toISOString(),
            }
        },

        async reDetect() {
            try {
                const fp = await FingerprintJS.load()
                const { visitorId } = await fp.get()

                const res = await fetch('/login/re-verify-device', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ deviceFingerprint: visitorId })
                })

                if (!res.ok) {
                    const data = await res.json()
                    this.handleReDetectError(data.code)
                    return
                }

                window.location.href = '/login/restaurant-pin'

            } catch (e) {
                this.$store.notif.set('error', 'Error', 'Ada Kesalahan, Coba lagi nanti')
            }
        },

        handleReDetectError(code) {
            const messages = {
                'invalid_credential': 'Email atau Password anda Salah',
                'disabled': 'Akun Telah dinonaktifkan',
                'invalid_fp': 'UID Tidak Berhasil Dikirim',
                'not_trusted': 'Device Belum Dikenali',
            }
            this.$store.notif.set('error', 'Error', messages[code] ?? 'Ada Kesalahan, Coba lagi nanti')
        },

        async requestTrust() {
            if (!this.deviceName) return
            try {
                const fp = await FingerprintJS.load()
                const { visitorId } = await fp.get()

                const res = await fetch('/login/request-trust', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ ...this.buildPayload(), deviceFingerprint: visitorId })
                })

                if (!res.ok) {
                    const data = await res.json()
                    const code = data.code

                    if (code === 'invalid_fp' || code === 'not_trusted') {
                        this.$store.notif.set('error', 'Error', 'Device Baru Terdeteksi')
                        return
                    }

                    const messages = {
                        'invalid_credential': 'Email atau Password anda Salah',
                        'disabled': 'Akun Telah dinonaktifkan',
                    }
                    this.$store.notif.set('error', 'Error', messages[code] ?? 'Ada Kesalahan')
                    return
                }

                this.$store.notif.set('success', 'Berhasil', 'Anda Akan Mendapat Email Saat Device Berhasil di Verifikasi')
                setTimeout(() => {
                    this.$store.deviceModal.open = false
                }, 500)

            } catch (e) {
                this.$store.notif.set('error', 'Error', 'Ada Kesalahan, Coba lagi nanti')
            }
        }
    }
}
</script>