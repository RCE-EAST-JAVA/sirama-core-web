{{--
    Komponen OCR Result Panel
    Props:
    - $ocrResults: Collection of OcrResult models
    - $fieldName: filter berdasarkan field tertentu (opsional)
--}}
@props(['ocrResults', 'fieldName' => null])

@php
    $results = $fieldName
        ? $ocrResults->where('field_dokumen', $fieldName)
        : $ocrResults;
@endphp

@if($results->isNotEmpty())
    <div class="space-y-3">
        @foreach($results as $result)
            <div class="rounded-lg border border-gray-200 bg-gray-50 overflow-hidden">
                <div class="flex items-center justify-between px-4 py-2.5 bg-white border-b border-gray-200">
                    <span class="text-xs font-medium text-gray-700">
                        Hasil OCR: {{ $result->field_dokumen }}
                    </span>
                    @if($result->confidence_score)
                        @php $pct = round($result->confidence_score * 100); @endphp
                        <span class="text-xs px-1.5 py-0.5 rounded font-medium
                            {{ $pct >= 80 ? 'bg-green-50 text-green-700' : ($pct >= 60 ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') }}">
                            {{ $pct }}% akurasi
                        </span>
                    @endif
                </div>
                <div class="px-4 py-3">
                    @if($result->hasil_ocr && count($result->hasil_ocr) > 0)
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">
                            @foreach($result->hasil_ocr as $key => $value)
                                <div>
                                    <dt class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}</dt>
                                    <dd class="text-sm text-gray-900 font-medium mt-0.5">{{ $value ?? '-' }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <p class="text-sm text-gray-400">Tidak ada data yang berhasil diekstrak.</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="rounded-lg border border-dashed border-gray-200 px-4 py-6 text-center">
        <p class="text-sm text-gray-400">Belum ada hasil OCR untuk dokumen ini.</p>
    </div>
@endif
