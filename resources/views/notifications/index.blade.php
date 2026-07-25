<x-dashboard-layout>
    <x-slot name="title">Notifikasi</x-slot>
    <x-slot name="pageTitle">Notifikasi</x-slot>

    {{-- Header Actions --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifikasi</h1>
            <p class="text-sm text-gray-500 mt-1">Lihat semua notifikasi dan update terbaru</p>
        </div>
        @if($notifications->where('read_at', null)->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                    Tandai Semua Sudah Dibaca
                </button>
            </form>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        @forelse($notifications as $notification)
            <a href="{{ route('notifications.show', $notification->id) }}" 
               class="block px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                <div class="flex items-start gap-4">
                    {{-- Icon --}}
                    <div class="flex-shrink-0 mt-1">
                        @php
                            $iconColor = match($notification->data['color'] ?? 'gray') {
                                'warning' => 'text-yellow-600 bg-yellow-100',
                                'success' => 'text-green-600 bg-green-100',
                                'danger' => 'text-red-600 bg-red-100',
                                'info' => 'text-blue-600 bg-blue-100',
                                default => 'text-gray-600 bg-gray-100',
                            };
                        @endphp
                        <div class="w-10 h-10 rounded-full {{ $iconColor }} flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if(($notification->data['icon'] ?? 'bell') === 'file-text')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                @endif
                            </svg>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-900 {{ is_null($notification->read_at) ? 'font-bold' : '' }}">
                                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                    {{ $notification->data['message'] ?? 'Tidak ada pesan' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-2">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            
                            {{-- Unread Badge --}}
                            @if(is_null($notification->read_at))
                                <span class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full"></span>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="px-6 py-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <h3 class="text-sm font-medium text-gray-900 mt-4">Tidak ada notifikasi</h3>
                <p class="text-sm text-gray-500 mt-1">Anda belum memiliki notifikasi apapun</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</x-dashboard-layout>
