<x-dashboard-layout>
    <x-slot name="title">Detail Notifikasi</x-slot>
    <x-slot name="pageTitle">Detail Notifikasi</x-slot>

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('notifications.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Notifikasi
        </a>
    </div>

    {{-- Notification Detail Card --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4 flex-1">
                    {{-- Icon --}}
                    <div class="flex-shrink-0">
                        @php
                            $iconColor = match($notification->data['color'] ?? 'gray') {
                                'warning' => 'text-yellow-600 bg-yellow-100',
                                'success' => 'text-green-600 bg-green-100',
                                'danger' => 'text-red-600 bg-red-100',
                                'info' => 'text-blue-600 bg-blue-100',
                                default => 'text-gray-600 bg-gray-100',
                            };
                        @endphp
                        <div class="w-12 h-12 rounded-full {{ $iconColor }} flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if(($notification->data['icon'] ?? 'bell') === 'file-text')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                @endif
                            </svg>
                        </div>
                    </div>

                    {{-- Title & Meta --}}
                    <div class="flex-1">
                        <h1 class="text-xl font-bold text-gray-900">
                            {{ $notification->data['title'] ?? 'Notifikasi' }}
                        </h1>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                            <span>{{ $notification->created_at->format('d M Y, H:i') }}</span>
                            <span>•</span>
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                            @if($notification->read_at)
                                <span>•</span>
                                <span class="text-green-600">Sudah dibaca</span>
                            @else
                                <span>•</span>
                                <span class="text-blue-600">Belum dibaca</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    @if(!$notification->read_at)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-2 text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                Tandai Sudah Dibaca
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 text-sm text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="px-6 py-6">
            <p class="text-gray-700 leading-relaxed">
                {{ $notification->data['message'] ?? 'Tidak ada pesan' }}
            </p>

            {{-- Additional Info --}}
            @if(!empty($notification->data['pengajuan_id']))
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Detail Pengajuan</h3>
                    <dl class="space-y-2">
                        @if(!empty($notification->data['pengajuan_id']))
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-600">ID Pengajuan:</dt>
                                <dd class="text-gray-900 font-medium">#{{ $notification->data['pengajuan_id'] }}</dd>
                            </div>
                        @endif
                        @if(!empty($notification->data['jenis_layanan']))
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-600">Jenis Layanan:</dt>
                                <dd class="text-gray-900 font-medium">{{ strtoupper(str_replace('_', ' ', $notification->data['jenis_layanan'])) }}</dd>
                            </div>
                        @endif
                        @if(!empty($notification->data['pemohon_nama']))
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-600">Pemohon:</dt>
                                <dd class="text-gray-900 font-medium">{{ $notification->data['pemohon_nama'] }}</dd>
                            </div>
                        @endif
                        @if(!empty($notification->data['pemohon_nik']))
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-600">NIK:</dt>
                                <dd class="text-gray-900 font-medium">{{ $notification->data['pemohon_nik'] }}</dd>
                            </div>
                        @endif
                        @if(!empty($notification->data['desa']))
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-600">Desa:</dt>
                                <dd class="text-gray-900 font-medium">{{ $notification->data['desa'] }}</dd>
                            </div>
                        @endif
                        @if(!empty($notification->data['status']))
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-600">Status:</dt>
                                <dd class="text-gray-900 font-medium">{{ ucfirst(str_replace('_', ' ', $notification->data['status'])) }}</dd>
                            </div>
                        @endif
                    </dl>

                    {{-- Action Button --}}
                    @if(!empty($notification->data['pengajuan_id']))
                        <div class="mt-4">
                            @php
                                $role = auth()->user()->role;
                                $pengajuanUrl = match($role) {
                                    'admin_aplikasi' => $notification->data['pengajuan_id'] ? route('admin.pengajuan.show', $notification->data['pengajuan_id']) : '#',
                                    'admin_desa' => $notification->data['pengajuan_id'] ? route('desa.pengajuan.show', $notification->data['pengajuan_id']) : '#',
                                    'admin_kecamatan' => $notification->data['pengajuan_id'] ? route('kecamatan.pengajuan.show', $notification->data['pengajuan_id']) : '#',
                                    default => '#',
                                };
                            @endphp
                            <a href="{{ $pengajuanUrl }}" class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                                Lihat Detail Pengajuan
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
