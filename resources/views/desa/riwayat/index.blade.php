<x-dashboard-layout title="Riwayat Pengajuan" pageTitle="Riwayat Pengajuan">

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('desa.riwayat') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">Semua Status</option>
                    <option value="diverifikasi_desa"  {{ request('status') === 'diverifikasi_desa'  ? 'selected' : '' }}>Diverifikasi Desa</option>
                    <option value="diverifikasi_kecamatan" {{ request('status') === 'diverifikasi_kecamatan' ? 'selected' : '' }}>Diverifikasi Kecamatan</option>
                    <option value="selesai"            {{ request('status') === 'selesai'            ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak_kecamatan"  {{ request('status') === 'ditolak_kecamatan'  ? 'selected' : '' }}>Ditolak Kecamatan</option>
                    <option value="ditolak_desa"       {{ request('status') === 'ditolak_desa'       ? 'selected' : '' }}>Ditolak Desa</option>
                    <option value="diajukan_kembali"  {{ request('status') === 'diajukan_kembali'  ? 'selected' : '' }}>Diajukan Kembali</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Layanan</label>
                <select name="jenis_layanan" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">Semua Layanan</option>
                    <option value="kia"            {{ request('jenis_layanan') === 'kia'            ? 'selected' : '' }}>KIA</option>
                    <option value="3_in_1"         {{ request('jenis_layanan') === '3_in_1'         ? 'selected' : '' }}>3 in 1</option>
                    <option value="kk_penambahan"  {{ request('jenis_layanan') === 'kk_penambahan'  ? 'selected' : '' }}>Penambahan Anggota KK</option>
                    <option value="kk_pengurangan" {{ request('jenis_layanan') === 'kk_pengurangan' ? 'selected' : '' }}>Pengurangan Anggota KK</option>
                    <option value="kk_perbaikan"   {{ request('jenis_layanan') === 'kk_perbaikan'   ? 'selected' : '' }}>Perbaikan Data KK</option>
                    <option value="akta_kelahiran" {{ request('jenis_layanan') === 'akta_kelahiran' ? 'selected' : '' }}>Akta Kelahiran</option>
                    <option value="akta_kematian"  {{ request('jenis_layanan') === 'akta_kematian'  ? 'selected' : '' }}>Akta Kematian</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                    class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                    class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="text-sm bg-brand-600 hover:bg-brand-700 text-white font-medium px-4 py-2 rounded-lg transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['status', 'jenis_layanan', 'tanggal_dari', 'tanggal_sampai']))
                <a href="{{ route('desa.riwayat') }}"
                    class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg transition-colors">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl border border-gray-200">
        @if($pengajuans->isEmpty())
            <div class="px-6 py-16 text-center">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm text-gray-500">Belum ada riwayat pengajuan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pemohon</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Layanan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Diperbarui</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pengajuans as $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-400 font-mono text-xs">#{{ $p->id }}</td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $p->user->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $p->user->nik }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $p->getLabelJenisLayanan() }}</td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$p->status" :label="$p->getLabelStatus()" />
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $p->updated_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('desa.pengajuan.show', $p) }}"
                                   class="text-brand-600 hover:text-brand-800 font-medium">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($pengajuans->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $pengajuans->links() }}
                </div>
            @endif
        @endif
    </div>

</x-dashboard-layout>
