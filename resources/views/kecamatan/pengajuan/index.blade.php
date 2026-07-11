<x-dashboard-layout title="Daftar Pengajuan" pageTitle="Daftar Pengajuan Kecamatan">

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('kecamatan.pengajuan.index') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Perlu Tindak Lanjut</option>
                    <option value="diverifikasi_desa"   {{ request('status') === 'diverifikasi_desa'   ? 'selected' : '' }}>Diverifikasi Desa</option>
                    <option value="diproses_kecamatan"  {{ request('status') === 'diproses_kecamatan'  ? 'selected' : '' }}>Diproses Kecamatan</option>
                    <option value="ditolak_kecamatan"   {{ request('status') === 'ditolak_kecamatan'   ? 'selected' : '' }}>Ditolak Kecamatan</option>
                    <option value="selesai"             {{ request('status') === 'selesai'             ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Layanan</label>
                <select name="jenis_layanan" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
            <button type="submit" class="text-sm bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['status', 'jenis_layanan']))
                <a href="{{ route('kecamatan.pengajuan.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">
                {{ $pengajuans->total() }} pengajuan ditemukan
            </h2>
        </div>

        @if($pengajuans->isEmpty())
            <div class="px-6 py-16 text-center text-gray-400 text-sm">
                Tidak ada pengajuan yang sesuai filter.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">ID</th>
                            <th class="px-6 py-3 text-left">Pemohon</th>
                            <th class="px-6 py-3 text-left">Desa</th>
                            <th class="px-6 py-3 text-left">Jenis Layanan</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pengajuans as $p)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-gray-500">#{{ $p->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $p->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $p->user->nik }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $p->user->desa ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $p->getLabelJenisLayanan() }}</td>
                                <td class="px-6 py-4">
                                    <x-status-badge :status="$p->status" :label="$p->getLabelStatus()" />
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $p->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('kecamatan.pengajuan.show', $p) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        Tinjau
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
