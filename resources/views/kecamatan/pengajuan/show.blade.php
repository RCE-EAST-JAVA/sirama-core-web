<x-dashboard-layout title="Detail Pengajuan" :pageTitle="'Pengajuan #' . $pengajuan->id">

    <div class="mb-6">
        <a href="{{ route('kecamatan.pengajuan.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
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
                        <p class="text-xs text-gray-500 mb-0.5">Nama Lengkap</p>
                        <p class="font-medium text-gray-900">{{ $pengajuan->nama_lengkap ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">NIK</p>
                        <p class="font-mono text-gray-700">{{ $pengajuan->nik ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">No. WhatsApp</p>
                        <p class="text-gray-700">{{ $pengajuan->no_whatsapp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Tanggal Lahir</p>
                        <p class="text-gray-700">{{ $pengajuan->tanggal_lahir?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Jenis Kelamin</p>
                        <p class="text-gray-700">{{ $pengajuan->jenis_kelamin === 'L' ? 'Laki-laki' : ($pengajuan->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Pekerjaan</p>
                        <p class="text-gray-700">{{ $pengajuan->pekerjaan ?? '-' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 mb-0.5">Alamat</p>
                        <p class="text-gray-700">{{ $pengajuan->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Desa</p>
                        <p class="text-gray-700">{{ $pengajuan->desa ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">RT / RW</p>
                        <p class="text-gray-700">{{ $pengajuan->rt ?? '-' }} / {{ $pengajuan->rw ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Detail Form --}}
            @if($formDetail)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Detail Berkas</h3>
                @php
                    $fileFields = method_exists($formDetail, 'getFileDokumen')
                        ? array_keys($formDetail->getFileDokumen())
                        : [];
                    $skipFields = array_merge(['id', 'pengajuan_id', 'created_at', 'updated_at'], $fileFields);
                @endphp
                <div class="grid grid-cols-2 gap-4 text-sm">
                    @foreach($formDetail->getAttributes() as $key => $value)
                        @if(!in_array($key, $skipFields) && !is_null($value))
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

        {{-- Kolom Kanan: Aksi Proses + Softfile --}}
        <div class="space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
                {{ session('error') }}
            </div>
            @endif

            {{-- Form Keputusan --}}
            @if(in_array($pengajuan->status, ['diverifikasi_desa', 'diverifikasi_kecamatan']))
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Proses Pengajuan</h3>

                <form method="POST" action="{{ route('kecamatan.pengajuan.proses', $pengajuan) }}"
                      x-data="{ aksi: '' }">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-600 mb-2">Keputusan</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="aksi" value="approve" x-model="aksi"
                                    class="text-brand-600 focus:ring-brand-500">
                                <span class="text-sm text-gray-700">Proses Pengajuan</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="aksi" value="selesai" x-model="aksi"
                                    class="text-brand-600 focus:ring-brand-500">
                                <span class="text-sm text-gray-700">Tandai Selesai</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="aksi" value="tolak" x-model="aksi"
                                    class="text-brand-600 focus:ring-brand-500">
                                <span class="text-sm text-gray-700">Tolak & Kembalikan ke Desa</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4" x-show="aksi !== ''" x-transition>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Catatan
                            <span x-show="aksi === 'tolak'" class="text-red-500">*</span>
                            <span x-show="aksi !== 'tolak'" class="text-gray-400">(opsional)</span>
                        </label>
                        <textarea name="catatan" rows="3"
                            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500"
                            placeholder="Catatan keputusan..."></textarea>
                    </div>

                    <button type="submit"
                        x-bind:disabled="aksi === ''"
                        x-bind:class="aksi === '' ? 'opacity-50 cursor-not-allowed' : 'hover:bg-brand-700'"
                        class="w-full bg-brand-600 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
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

            {{-- Softfile --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Softfile Dokumen</h3>

                {{-- List softfile yang sudah ada --}}
                @if(!empty($pengajuan->lokasi_dokumen))
                <ul class="space-y-2 mb-4">
                    @foreach($pengajuan->lokasi_dokumen as $filePath)
                    <li class="flex items-center justify-between gap-2 text-sm bg-gray-50 rounded-lg px-3 py-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                            <span class="truncate text-gray-700">{{ basename($filePath) }}</span>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <a href="{{ route('dokumen.softfile', [$pengajuan, $loop->index]) }}"
                               class="text-xs text-brand-600 hover:text-brand-800 font-medium px-2 py-1 rounded hover:bg-brand-50 transition-colors">
                                Unduh
                            </a>
                            <form method="POST"
                                  action="{{ route('kecamatan.pengajuan.softfile.hapus', $pengajuan) }}"
                                  onsubmit="return confirm('Hapus file ini?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="path" value="{{ $filePath }}">
                                <button type="submit"
                                    class="text-xs text-red-500 hover:text-red-700 font-medium px-2 py-1 rounded hover:bg-red-50 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-xs text-gray-400 mb-4">Belum ada softfile.</p>
                @endif

                {{-- Form upload softfile baru --}}
                <form method="POST"
                      action="{{ route('kecamatan.pengajuan.softfile.upload', $pengajuan) }}"
                      enctype="multipart/form-data"
                      x-data="{ files: [] }">
                    @csrf
                    <label class="block text-xs font-medium text-gray-600 mb-1">Upload Softfile (PDF)</label>
                    <input type="file" name="softfile[]" accept=".pdf" multiple
                        x-on:change="files = Array.from($event.target.files).map(f => f.name)"
                        class="w-full text-sm text-gray-500 file:mr-3 file:text-xs file:font-medium file:bg-brand-50 file:text-brand-700 file:border-0 file:rounded-lg file:px-3 file:py-1.5 hover:file:bg-brand-100 cursor-pointer">
                    <p class="text-xs text-gray-400 mt-1">Bisa pilih lebih dari satu. Format PDF, maks. 10MB per file.</p>

                    <template x-if="files.length > 0">
                        <ul class="mt-2 space-y-1">
                            <template x-for="name in files" :key="name">
                                <li class="text-xs text-gray-600 flex items-center gap-1">
                                    <span class="text-gray-400">&#8250;</span>
                                    <span x-text="name"></span>
                                </li>
                            </template>
                        </ul>
                    </template>

                    <button type="submit"
                        x-bind:disabled="files.length === 0"
                        x-bind:class="files.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-brand-700'"
                        class="mt-3 w-full bg-brand-600 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
                        Upload
                    </button>
                </form>
            </div>

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
