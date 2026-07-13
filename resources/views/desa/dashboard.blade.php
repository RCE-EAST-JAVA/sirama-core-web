<x-dashboard-layout>
    <x-slot name="title">Dashboard Desa {{ $desa }}</x-slot>
    <x-slot name="pageTitle">Dashboard - Desa {{ $desa }}</x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Total Pengajuan</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Menunggu Verifikasi</p>
            <p class="text-3xl font-bold text-brand-600 mt-1">{{ $stats['berkas_diterima'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Terverifikasi</p>
            <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['diverifikasi_desa'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-5">
            <p class="text-sm text-gray-500 font-medium">Ditolak</p>
            <p class="text-3xl font-bold text-red-500 mt-1">{{ $stats['ditolak_desa'] }}</p>
        </div>
    </div>

    {{-- Pengajuan Perlu Tindakan --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-900">Perlu Ditindaklanjuti</h2>
            <a href="{{ route('desa.pengajuan.index') }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium transition-colors">
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
                        <th class="text-left px-5 py-3 text-sm font-semibold text-gray-600">Tanggal</th>
                        <th class="text-left px-5 py-3 text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengajuan_terbaru as $pengajuan)
                        <tr class="hover:bg-brand-50 transition-colors">
                            <td class="px-5 py-4 text-gray-900 font-medium text-base">{{ $pengajuan->nama_lengkap }}</td>
                            <td class="px-5 py-4 text-gray-600 text-base">{{ $pengajuan->getLabelJenisLayanan() }}</td>
                            <td class="px-5 py-4"><x-status-badge :status="$pengajuan->status" /></td>
                            <td class="px-5 py-4 text-gray-500 text-base">{{ $pengajuan->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ route('desa.pengajuan.show', $pengajuan) }}"
                                    class="text-sm text-brand-600 hover:text-brand-700 font-semibold transition-colors">
                                    Periksa
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-base text-gray-400">
                                Tidak ada pengajuan yang perlu ditindaklanjuti.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
