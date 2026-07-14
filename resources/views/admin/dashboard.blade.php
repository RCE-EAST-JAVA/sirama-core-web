<x-dashboard-layout>
    <x-slot name="title">Dashboard Admin</x-slot>
    <x-slot name="pageTitle">Dashboard Admin</x-slot>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Total Pengajuan</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_pengajuan'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Selesai</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['selesai'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Sedang Diproses</p>
            <p class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['diverifikasi_kecamatan'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Ditolak</p>
            <p class="text-3xl font-bold text-red-500 mt-1">{{ $stats['ditolak_desa'] + $stats['ditolak_kecamatan'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        {{-- User Stats --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Pengguna Sistem</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-base text-gray-600">Admin Desa</span>
                    <span class="text-base font-semibold text-gray-900">{{ $stats['total_admin_desa'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-base text-gray-600">Admin Kecamatan</span>
                    <span class="text-base font-semibold text-gray-900">{{ $stats['total_admin_kecamatan'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-base text-gray-600">Warga Terdaftar</span>
                    <span class="text-base font-semibold text-gray-900">{{ $stats['total_warga'] }}</span>
                </div>
            </div>
        </div>

        {{-- Pengajuan Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h2 class="text-base font-semibold text-gray-900">Pengajuan Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left px-5 py-3 text-sm font-semibold text-gray-600">Pemohon</th>
                            <th class="text-left px-5 py-3 text-sm font-semibold text-gray-600">Jenis Layanan</th>
                            <th class="text-left px-5 py-3 text-sm font-semibold text-gray-600">Status</th>
                            <th class="text-left px-5 py-3 text-sm font-semibold text-gray-600">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pengajuan_terbaru as $pengajuan)
                            <tr class="hover:bg-brand-50 transition-colors">
                                <td class="px-5 py-4 text-gray-900 font-medium text-base">{{ $pengajuan->user->name }}</td>
                                <td class="px-5 py-4 text-gray-600 text-base">{{ $pengajuan->getLabelJenisLayanan() }}</td>
                                <td class="px-5 py-4"><x-status-badge :status="$pengajuan->status" /></td>
                                <td class="px-5 py-4 text-gray-500 text-base">{{ $pengajuan->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-base text-gray-400">Belum ada pengajuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard-layout>
