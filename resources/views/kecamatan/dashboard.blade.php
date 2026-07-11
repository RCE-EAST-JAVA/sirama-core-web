<x-dashboard-layout>
    <x-slot name="title">Dashboard Kecamatan</x-slot>
    <x-slot name="pageTitle">Dashboard Kecamatan</x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Pengajuan</p>
            <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Menunggu Proses</p>
            <p class="text-2xl font-semibold text-yellow-600 mt-1">{{ $stats['menunggu_verifikasi'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Sedang Diproses</p>
            <p class="text-2xl font-semibold text-purple-600 mt-1">{{ $stats['diproses'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Selesai</p>
            <p class="text-2xl font-semibold text-green-600 mt-1">{{ $stats['selesai'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        {{-- Per jenis layanan --}}
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Pengajuan per Jenis</h2>
            @php
                $jenisLabels = [
                    'kia'            => 'KIA',
                    '3_in_1'         => '3 in 1',
                    'kk_penambahan'  => 'KK Penambahan',
                    'kk_pengurangan' => 'KK Pengurangan',
                    'kk_perbaikan'   => 'KK Perbaikan',
                    'akta_kelahiran' => 'Akta Kelahiran',
                    'akta_kematian'  => 'Akta Kematian',
                ];
            @endphp
            <div class="space-y-2">
                @foreach($jenisLabels as $key => $label)
                    <div class="flex items-center justify-between py-1 border-b border-gray-50 last:border-0">
                        <span class="text-sm text-gray-600">{{ $label }}</span>
                        <span class="text-sm font-medium text-gray-900">{{ $per_jenis[$key] ?? 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pengajuan terbaru --}}
        <div class="bg-white rounded-lg border border-gray-200 lg:col-span-2">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h2 class="text-sm font-semibold text-gray-900">Perlu Ditindaklanjuti</h2>
                <a href="{{ route('kecamatan.pengajuan.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                    Lihat semua
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                            <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pengajuan_terbaru as $pengajuan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3 text-gray-900 font-medium">{{ $pengajuan->user->name }}</td>
                                <td class="px-5 py-3 text-gray-600 text-xs">{{ $pengajuan->getLabelJenisLayanan() }}</td>
                                <td class="px-5 py-3"><x-status-badge :status="$pengajuan->status" /></td>
                                <td class="px-5 py-3">
                                    <a href="{{ route('kecamatan.pengajuan.show', $pengajuan) }}"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                        Proses
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-400">
                                    Tidak ada pengajuan yang perlu diproses.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dashboard-layout>
