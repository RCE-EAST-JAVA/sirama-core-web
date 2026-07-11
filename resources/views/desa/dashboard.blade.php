<x-dashboard-layout>
    <x-slot name="title">Dashboard Desa {{ $desa }}</x-slot>
    <x-slot name="pageTitle">Dashboard - Desa {{ $desa }}</x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Pengajuan</p>
            <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Menunggu Verifikasi</p>
            <p class="text-2xl font-semibold text-blue-600 mt-1">{{ $stats['berkas_diterima'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Terverifikasi</p>
            <p class="text-2xl font-semibold text-yellow-600 mt-1">{{ $stats['diverifikasi_desa'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 px-5 py-4">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Ditolak</p>
            <p class="text-2xl font-semibold text-red-500 mt-1">{{ $stats['ditolak_desa'] }}</p>
        </div>
    </div>

    {{-- Pengajuan Perlu Tindakan --}}
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
            <h2 class="text-sm font-semibold text-gray-900">Perlu Ditindaklanjuti</h2>
            <a href="{{ route('desa.pengajuan.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                Lihat semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Layanan</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengajuan_terbaru as $pengajuan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-900 font-medium">{{ $pengajuan->user->name }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $pengajuan->getLabelJenisLayanan() }}</td>
                            <td class="px-5 py-3"><x-status-badge :status="$pengajuan->status" /></td>
                            <td class="px-5 py-3 text-gray-500">{{ $pengajuan->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-3">
                                <a href="{{ route('desa.pengajuan.show', $pengajuan) }}"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                    Periksa
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-400">
                                Tidak ada pengajuan yang perlu ditindaklanjuti.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
