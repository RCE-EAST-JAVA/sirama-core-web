{{--
    Komponen notification bell.
    Akan diisi dengan data real-time via Laravel Echo + Reverb pada Phase 6.
    Untuk sekarang menampilkan placeholder UI.
--}}
<div x-data="{
    open: false,
    notifications: [],
    unread: 0,
    init() {
        // Phase 6: Echo listener akan diinisialisasi di sini
        // window.Echo.private('pengajuan.' + userId).listen('.status.updated', (e) => {
        //     this.notifications.unshift(e);
        //     this.unread++;
        // });
    }
}" class="relative">

    <button @click="open = !open"
        class="relative p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors"
        aria-label="Notifikasi">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <span x-show="unread > 0"
            class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"
            style="display: none;">
        </span>
    </button>

    <div x-show="open"
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg ring-1 ring-gray-200 z-50"
        style="display: none;">

        <div class="px-4 py-3 border-b border-gray-100">
            <p class="text-sm font-medium text-gray-900">Notifikasi</p>
        </div>

        <div class="max-h-80 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-8 text-center text-sm text-gray-400">
                    Tidak ada notifikasi
                </div>
            </template>

            <template x-for="(notif, index) in notifications" :key="index">
                <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <p class="text-sm text-gray-800" x-text="notif.jenis_layanan"></p>
                    <p class="text-xs text-gray-500 mt-0.5" x-text="notif.status_label"></p>
                    <p class="text-xs text-gray-400 mt-0.5" x-text="notif.updated_at"></p>
                </div>
            </template>
        </div>
    </div>
</div>
