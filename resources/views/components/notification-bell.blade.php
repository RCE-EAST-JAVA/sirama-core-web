<div x-data="notificationBell()" class="relative">
    <button @click="toggleDropdown()"
        class="relative p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors"
        aria-label="Notifikasi">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <span x-show="unreadCount > 0"
            x-text="unreadCount"
            class="absolute -top-1 -right-1 min-w-[18px] h-[18px] flex items-center justify-center px-1 text-[10px] font-bold text-white bg-red-500 rounded-full"
            style="display: none;">
        </span>
    </button>

    <div x-show="open"
        @click.outside="closeDropdown()"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg ring-1 ring-gray-200 z-50"
        style="display: none;">

        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-900">Notifikasi</p>
            <button x-show="unreadCount > 0" @click="markAllAsRead()"
                class="text-xs text-brand-600 hover:text-brand-700 transition-colors">
                Tandai semua sudah dibaca
            </button>
        </div>

        <div class="max-h-96 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-8 text-center text-sm text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Tidak ada notifikasi
                </div>
            </template>

            <template x-for="notif in notifications" :key="notif.id">
                <div @click="handleNotificationClick(notif)"
                    class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer"
                    :class="{ 'bg-blue-50/30': !notif.read_at }">
                    <div class="flex items-start gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span x-show="!notif.read_at" class="w-2 h-2 bg-blue-500 rounded-full shrink-0"></span>
                                <p class="text-sm font-medium text-gray-900 truncate" x-text="notif.data?.title || ''"></p>
                            </div>
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2" x-text="notif.data?.message || ''"></p>
                            <p class="text-xs text-gray-400 mt-1" x-text="formatDate(notif.created_at)"></p>
                        </div>
                        <button @click.stop="deleteNotification(notif.id)"
                            class="text-gray-400 hover:text-red-500 transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div class="px-4 py-2 border-t border-gray-100 text-center">
            <a href="{{ Route::has('notifications.index') ? route('notifications.index') : '#' }}" class="text-xs text-brand-600 hover:text-brand-700 transition-colors">
                Lihat semua notifikasi
            </a>
        </div>
    </div>
</div>