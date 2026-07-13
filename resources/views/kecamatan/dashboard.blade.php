<x-dashboard-layout>
    <x-slot name="title">Dashboard Kecamatan</x-slot>
    <x-slot name="pageTitle">Dashboard Kecamatan</x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Total Pengajuan</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Menunggu Proses</p>
            <p class="text-3xl font-bold text-brand-600 mt-1">{{ $stats['menunggu_verifikasi'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Sedang Diproses</p>
            <p class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['diproses'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Selesai</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['selesai'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        {{-- Per jenis layanan --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Pengajuan per Jenis</h2>
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
            <div class="space-y-1">
                @foreach($jenisLabels as $key => $label)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                        <span class="text-base text-gray-600">{{ $label }}</span>
                        <span class="text-base font-semibold text-gray-900">{{ $per_jenis[$key] ?? 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pengajuan Perlu Diproses --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                <h2 class="text-base font-semibold text-gray-900">Perlu Diproses</h2>
                <a href="{{ route('kecamatan.pengajuan.index') }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium transition-colors">
                    Lihat semua &rarr;
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left px-5 py-3 text-sm font-semibold text-gray-600">Pemohon</th>
                            <th class="text-left px-5 py-3 text-sm font-semibold text-gray-600">Jenis Layanan</th>
                            <th class="text-left px-5 py-3 text-sm font-semibold text-gray-600">Status</th>
                            <th class="text-left px-5 py-3 text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pengajuan_terbaru as $pengajuan)
                            <tr class="hover:bg-brand-50 transition-colors">
                                <td class="px-5 py-4 text-gray-900 font-medium text-base">{{ $pengajuan->nama_lengkap }}</td>
                                <td class="px-5 py-4 text-gray-600 text-base">{{ $pengajuan->getLabelJenisLayanan() }}</td>
                                <td class="px-5 py-4"><x-status-badge :status="$pengajuan->status" /></td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('kecamatan.pengajuan.show', $pengajuan) }}"
                                        class="text-sm text-brand-600 hover:text-brand-700 font-semibold transition-colors">
                                        Proses
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-base text-gray-400">
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
