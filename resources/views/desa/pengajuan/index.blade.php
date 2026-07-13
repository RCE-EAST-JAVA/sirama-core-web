<x-dashboard-layout title="Daftar Pengajuan" pageTitle="Daftar Pengajuan Masuk">

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
        <form method="GET" action="{{ route('desa.pengajuan.index') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">Perlu Tindak Lanjut</option>
                    <option value="berkas_diterima"   {{ request('status') === 'berkas_diterima'   ? 'selected' : '' }}>Berkas Diterima</option>
                    <option value="ditolak_desa"      {{ request('status') === 'ditolak_desa'      ? 'selected' : '' }}>Ditolak Desa</option>
                    <option value="diverifikasi_desa" {{ request('status') === 'diverifikasi_desa' ? 'selected' : '' }}>Diverifikasi Desa</option>
                    <option value="selesai"           {{ request('status') === 'selesai'           ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Layanan</label>
                <select name="jenis_layanan" class="text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500">
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
            <button type="submit" class="text-base bg-brand-600 text-white px-5 py-2.5 rounded-lg hover:bg-brand-700 font-medium transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['status', 'jenis_layanan']))
                <a href="{{ route('desa.pengajuan.index') }}" class="text-base text-gray-500 hover:text-brand-600 px-3 py-2.5">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-700">
                {{ $pengajuans->total() }} pengajuan ditemukan
            </h2>
        </div>

        @if($pengajuans->isEmpty())
            <div class="px-6 py-16 text-center text-gray-400 text-base">
                Tidak ada pengajuan yang sesuai filter.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Pemohon</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Jenis Layanan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pengajuans as $p)
                            <tr class="hover:bg-brand-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-gray-500 text-base">#{{ $p->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900 text-base">{{ $p->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $p->user->nik }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-700 text-base">{{ $p->getLabelJenisLayanan() }}</td>
                                <td class="px-6 py-4">
                                    <x-status-badge :status="$p->status" :label="$p->getLabelStatus()" />
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-base">{{ $p->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('desa.pengajuan.show', $p) }}"
                                       class="text-base text-brand-600 hover:text-brand-700 font-semibold">
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
