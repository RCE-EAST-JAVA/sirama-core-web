<x-dashboard-layout title="Detail Pengajuan" :pageTitle="'Pengajuan #' . $pengajuan->id">

    <div class="mb-6">
        <a href="{{ route('desa.pengajuan.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            &larr; Kembali ke daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Info Pemohon + Detail Form --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Info Pengajuan --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">{{ $pengajuan->getLabelJenisLayanan() }}</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Diajukan {{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <x-status-badge :status="$pengajuan->status" :label="$pengajuan->getLabelStatus()" />
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Nama Pemohon</p>
                        <p class="font-medium text-gray-900">{{ $pengajuan->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">NIK</p>
                        <p class="font-mono text-gray-700">{{ $pengajuan->user->nik }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">No. WhatsApp</p>
                        <p class="text-gray-700">{{ $pengajuan->no_whatsapp ?? $pengajuan->user->no_whatsapp }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Desa</p>
                        <p class="text-gray-700">{{ $pengajuan->user->desa ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Detail Form --}}
            @if($formDetail)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Detail Berkas</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    @foreach($formDetail->getAttributes() as $key => $value)
                        @if(!in_array($key, ['id', 'pengajuan_id', 'created_at', 'updated_at']) && !is_null($value))
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5 capitalize">{{ str_replace('_', ' ', $key) }}</p>
                                <p class="text-gray-700">
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
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-2">Dokumen Pendukung</p>
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
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Riwayat Status</h3>
                <ol class="relative border-l border-gray-200 space-y-4 ml-3">
                    @foreach($pengajuan->riwayatStatuses->sortByDesc('created_at') as $riwayat)
                    <li class="ml-4">
                        <div class="absolute w-2.5 h-2.5 bg-gray-300 rounded-full mt-1.5 -left-1.5 border border-white"></div>
                        <time class="text-xs text-gray-400">{{ $riwayat->created_at->format('d M Y, H:i') }}</time>
                        <p class="text-sm font-medium text-gray-800 mt-0.5">{{ $riwayat->status_riwayat }}</p>
                        @if($riwayat->catatan)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $riwayat->catatan }}</p>
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
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Verifikasi Pengajuan</h3>

                <form method="POST" action="{{ route('desa.pengajuan.verifikasi', $pengajuan) }}" x-data="{ aksi: '' }">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-600 mb-2">Keputusan</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="aksi" value="approve" x-model="aksi"
                                    class="text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Setujui & Teruskan ke Kecamatan</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="aksi" value="tolak" x-model="aksi"
                                    class="text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Tolak & Minta Revisi</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4" x-show="aksi === 'tolak'" x-transition>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Catatan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="catatan" rows="3"
                            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>

                    <div class="mb-4" x-show="aksi === 'approve'" x-transition>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Catatan (opsional)</label>
                        <textarea name="catatan" rows="2"
                            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Catatan tambahan..."></textarea>
                    </div>

                    <button type="submit"
                        x-bind:disabled="aksi === ''"
                        x-bind:class="aksi === '' ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'"
                        class="w-full bg-blue-600 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                        Kirim Keputusan
                    </button>
                </form>
            </div>
            @else
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center">
                <p class="text-sm text-gray-500">Pengajuan ini sudah ditindaklanjuti.</p>
                <p class="text-xs text-gray-400 mt-1">Status: {{ $pengajuan->getLabelStatus() }}</p>
            </div>
            @endif

            {{-- OCR Results --}}
            @if($pengajuan->ocrResults->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Hasil Verifikasi OCR</h3>
                <x-ocr-result-panel :results="$pengajuan->ocrResults" />
            </div>
            @endif
        </div>
    </div>

</x-dashboard-layout>
