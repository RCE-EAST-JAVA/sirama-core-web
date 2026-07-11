<x-dashboard-layout>
    <x-slot name="title">Dashboard Admin</x-slot>
    <x-slot name="pageTitle">Dashboard Admin</x-slot>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Pengajuan</p>
            <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $stats['total_pengajuan'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Selesai</p>
            <p class="text-2xl font-semibold text-green-600 mt-1">{{ $stats['selesai'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Sedang Proses</p>
            <p class="text-2xl font-semibold text-purple-600 mt-1">{{ $stats['diproses_kecamatan'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Ditolak</p>
            <p class="text-2xl font-semibold text-red-500 mt-1">{{ $stats['ditolak_desa'] + $stats['ditolak_kecamatan'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        {{-- User Stats --}}
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Pengguna Sistem</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Admin Desa</span>
                    <span class="text-sm font-medium text-gray-900">{{ $stats['total_admin_desa'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Admin Kecamatan</span>
                    <span class="text-sm font-medium text-gray-900">{{ $stats['total_admin_kecamatan'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Warga Terdaftar</span>
                    <span class="text-sm font-medium text-gray-900">{{ $stats['total_warga'] }}</span>
                </div>
            </div>
        </div>

        {{-- Status Breakdown --}}
        <div class="bg-white rounded-lg border border-gray-200 p-5 lg:col-span-2">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Rekapitulasi Status Pengajuan</h2>
            <div class="space-y-2">
                @foreach([
                    ['key' => 'berkas_diterima',    'label' => 'Berkas Diterima'],
                    ['key' => 'diverifikasi_desa',   'label' => 'Diverifikasi Desa'],
                    ['key' => 'ditolak_desa',        'label' => 'Ditolak Desa'],
                    ['key' => 'diproses_kecamatan',  'label' => 'Diproses Kecamatan'],
                    ['key' => 'ditolak_kecamatan',   'label' => 'Ditolak Kecamatan'],
                    ['key' => 'selesai',             'label' => 'Selesai'],
                ] as $item)
                    <div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-2">
                            <x-status-badge :status="$item['key']" />
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $stats[$item['key']] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Pengajuan Terbaru --}}
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
            <h2 class="text-sm font-semibold text-gray-900">Pengajuan Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Layanan</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengajuan_terbaru as $pengajuan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-900 font-medium">{{ $pengajuan->user->name }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $pengajuan->getLabelJenisLayanan() }}</td>
                            <td class="px-5 py-3"><x-status-badge :status="$pengajuan->status" /></td>
                            <td class="px-5 py-3 text-gray-500">{{ $pengajuan->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">Belum ada pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
