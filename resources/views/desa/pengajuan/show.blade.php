<x-dashboard-layout title="Detail Pengajuan" :pageTitle="'Pengajuan #' . $pengajuan->id">

    <div class="mb-6">
        <a href="{{ route('desa.pengajuan.index') }}" class="text-base text-brand-600 hover:text-brand-700 font-medium">
            &larr; Kembali ke daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Info Pemohon + Detail Form --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Info Pengajuan --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $pengajuan->getLabelJenisLayanan() }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Diajukan {{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <x-status-badge :status="$pengajuan->status" :label="$pengajuan->getLabelStatus()" />
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                        <p class="text-base font-semibold text-gray-900">{{ $pengajuan->nama_lengkap ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">NIK</p>
                        <p class="text-base font-mono text-gray-700">{{ $pengajuan->nik ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">No. WhatsApp</p>
                        <p class="text-base text-gray-700">{{ $pengajuan->no_whatsapp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tanggal Lahir</p>
                        <p class="text-base text-gray-700">{{ $pengajuan->tanggal_lahir?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Jenis Kelamin</p>
                        <p class="text-base text-gray-700">{{ $pengajuan->jenis_kelamin === 'L' ? 'Laki-laki' : ($pengajuan->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Pekerjaan</p>
                        <p class="text-base text-gray-700">{{ $pengajuan->pekerjaan ?? '-' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500 mb-1">Alamat</p>
                        <p class="text-base text-gray-700">{{ $pengajuan->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Desa</p>
                        <p class="text-base text-gray-700">{{ $pengajuan->desa ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">RT / RW</p>
                        <p class="text-base text-gray-700">{{ $pengajuan->rt ?? '-' }} / {{ $pengajuan->rw ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Detail Form --}}
            @if($formDetail)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Detail Berkas</h3>
                @php
                    $fileFields = method_exists($formDetail, 'getFileDokumen')
                        ? array_keys($formDetail->getFileDokumen())
                        : [];
                    $skipFields = array_merge(['id', 'pengajuan_id', 'created_at', 'updated_at'], $fileFields);
                @endphp
                <div class="grid grid-cols-2 gap-5">
                    @foreach($formDetail->getAttributes() as $key => $value)
                        @if(!in_array($key, $skipFields) && !is_null($value))
                            <div>
                                <p class="text-sm text-gray-500 mb-1 capitalize">{{ str_replace('_', ' ', $key) }}</p>
                                <p class="text-base text-gray-700">
                                    @if(is_array(json_decode($value, true)) && json_last_error() === JSON_ERROR_NONE)
                                        {{ implode(', ', json_decode($value, true)) }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- File Dokumen --}}
                @if(method_exists($formDetail, 'getFileDokumen') && $formDetail->getFileDokumen())
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <p class="text-sm font-medium text-gray-600 mb-3">Dokumen Pendukung</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($formDetail->getFileDokumen() as $fieldName => $label)
                                @if($formDetail->$fieldName)
                                    <x-document-preview
                                        :pengajuan-id="$pengajuan->id"
                                        :field-name="$fieldName"
                                        :file-path="$formDetail->$fieldName"
                                        :label="$label" />
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            @endif

            {{-- Riwayat Status --}}
            @if($pengajuan->riwayatStatuses->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Riwayat Status</h3>
                <ol class="relative border-l-2 border-brand-200 space-y-4 ml-3">
                    @foreach($pengajuan->riwayatStatuses->sortByDesc('created_at') as $riwayat)
                    <li class="ml-5">
                        <div class="absolute w-3 h-3 bg-brand-400 rounded-full mt-1.5 -left-1.5 border-2 border-white"></div>
                        <time class="text-sm text-gray-400">{{ $riwayat->created_at->format('d M Y, H:i') }}</time>
                        <p class="text-base font-semibold text-gray-800 mt-0.5">{{ $riwayat->status_riwayat }}</p>
                        @if($riwayat->catatan)
                            <p class="text-sm text-gray-600 mt-0.5">{{ $riwayat->catatan }}</p>
                        @endif
                    </li>
                    @endforeach
                </ol>
            </div>
            @endif
        </div>

        {{-- Kolom Kanan: Aksi Verifikasi --}}
        <div class="space-y-6">

            {{-- Form Verifikasi --}}
            @if(in_array($pengajuan->status, ['berkas_diterima', 'ditolak_desa']))
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-5">Verifikasi Pengajuan</h3>

                <form method="POST" action="{{ route('desa.pengajuan.verifikasi', $pengajuan) }}" x-data="{ aksi: '' }">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Keputusan</label>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-gray-200 hover:border-brand-400 hover:bg-brand-50 transition-colors">
                                <input type="radio" name="aksi" value="approve" x-model="aksi"
                                    class="text-brand-600 focus:ring-brand-500 w-4 h-4">
                                <span class="text-base text-gray-700">Setujui &amp; Teruskan ke Kecamatan</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-gray-200 hover:border-red-400 hover:bg-red-50 transition-colors">
                                <input type="radio" name="aksi" value="tolak" x-model="aksi"
                                    class="text-brand-600 focus:ring-brand-500 w-4 h-4">
                                <span class="text-base text-gray-700">Tolak &amp; Minta Revisi</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-5" x-show="aksi === 'tolak'" x-transition>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="catatan" rows="3"
                            class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"
                            placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>

                    <div class="mb-5" x-show="aksi === 'approve'" x-transition>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (opsional)</label>
                        <textarea name="catatan" rows="2"
                            class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"
                            placeholder="Catatan tambahan..."></textarea>
                    </div>

                    <button type="submit"
                        x-bind:disabled="aksi === ''"
                        x-bind:class="aksi === '' ? 'opacity-50 cursor-not-allowed' : 'hover:bg-brand-700'"
                        class="w-full bg-brand-600 text-white text-base font-semibold px-4 py-3 rounded-lg transition-colors">
                        Kirim Keputusan
                    </button>
                </form>
            </div>
            @else
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center">
                <p class="text-base text-gray-500">Pengajuan ini sudah ditindaklanjuti.</p>
                <p class="text-sm text-gray-400 mt-1">Status: {{ $pengajuan->getLabelStatus() }}</p>
            </div>
            @endif

            {{-- OCR Results --}}
            @if($pengajuan->ocrResults->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Hasil Verifikasi OCR</h3>
                <x-ocr-result-panel :results="$pengajuan->ocrResults" />
            </div>
            @endif
        </div>
    </div>

</x-dashboard-layout>
