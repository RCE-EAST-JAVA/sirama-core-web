{{--
    Komponen Document Preview Modal
    Props:
    - $fieldName: nama field (string, misal 'file_kk')
    - $filePath: path file di storage (string)
    - $label: label tampilan (string)
    - $pengajuanId: ID pengajuan
--}}
@props(['fieldName', 'filePath' => null, 'label' => 'Lihat Dokumen', 'pengajuanId'])

@php
    if (!$filePath) return;
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $isPdf = $ext === 'pdf';
    $dataUrl = route('dokumen.data', ['pengajuan' => $pengajuanId, 'field' => $fieldName]);
    $showUrl = route('dokumen.show', ['pengajuan' => $pengajuanId, 'field' => $fieldName]);
@endphp

<div
    x-data="{
        open: false,
        loading: false,
        error: null,
        src: null,
        isPdf: {{ $isPdf ? 'true' : 'false' }},
        dataUrl: '{{ $dataUrl }}',
        async load() {
            if (this.src) return;
            this.loading = true;
            this.error = null;
            try {
                const res = await fetch(this.dataUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const json = await res.json();
                if (!res.ok) throw new Error(json.error || 'Gagal memuat file (status ' + res.status + ')');
                this.src = json.data;
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },
        openModal() {
            this.open = true;
            this.load();
        }
    }"
    class="inline-block">

    <button @click="openModal()"
        type="button"
        class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
        @if($isPdf)
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
        @else
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        @endif
        {{ $label }}
    </button>

    {{-- Modal --}}
    <div x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
        @click.self="open = false"
        @keydown.escape.window="open = false"
        style="display: none;">

        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] flex flex-col"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 shrink-0">
                <h3 class="text-sm font-semibold text-gray-900">{{ $label }}</h3>
                <div class="flex items-center gap-3">
                    <a href="{{ $showUrl }}"
                        target="_blank"
                        class="text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Buka di tab baru
                    </a>
                    <button @click="open = false"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                        aria-label="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="flex-1 overflow-auto p-4 flex items-center justify-center">

                {{-- Loading --}}
                <div x-show="loading" class="flex flex-col items-center gap-2 py-12">
                    <svg class="w-8 h-8 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <p class="text-sm text-gray-500">Memuat dokumen...</p>
                </div>

                {{-- Error --}}
                <div x-show="error && !loading" class="text-center py-12">
                    <p class="text-sm text-red-500" x-text="error"></p>
                </div>

                {{-- PDF Preview --}}
                <template x-if="src && isPdf && !loading">
                    <iframe
                        :src="src"
                        class="w-full min-h-96 rounded border border-gray-200"
                        style="height: 70vh;"
                        title="{{ $label }}">
                    </iframe>
                </template>

                {{-- Image Preview --}}
                <template x-if="src && !isPdf && !loading">
                    <img
                        :src="src"
                        alt="{{ $label }}"
                        class="max-w-full h-auto mx-auto rounded border border-gray-200">
                </template>
            </div>
        </div>
    </div>
</div>
