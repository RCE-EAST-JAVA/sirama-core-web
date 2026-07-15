@props(['status'])

@php
$config = match($status) {
    'berkas_diterima'    => ['label' => 'Berkas Diterima',    'class' => 'bg-brand-50 text-brand-700 ring-brand-600/20'],
    'ditolak_desa'       => ['label' => 'Ditolak Desa',       'class' => 'bg-red-50 text-red-700 ring-red-600/20'],
    'diverifikasi_desa'  => ['label' => 'Diverifikasi Desa',  'class' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20'],
    'ditolak_kecamatan'  => ['label' => 'Ditolak Kecamatan',  'class' => 'bg-red-50 text-red-700 ring-red-600/20'],
    'diverifikasi_kecamatan' => ['label' => 'Diverifikasi Kecamatan', 'class' => 'bg-purple-50 text-purple-700 ring-purple-600/20'],
    'selesai'            => ['label' => 'Selesai',            'class' => 'bg-green-50 text-green-700 ring-green-600/20'],
    'diajukan_kembali'   => ['label' => 'Diajukan Kembali',  'class' => 'bg-orange-50 text-orange-700 ring-orange-600/20'],
    default              => ['label' => $status,              'class' => 'bg-gray-50 text-gray-700 ring-gray-600/20'],
};
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ring-1 ring-inset {{ $config['class'] }}">
    {{ $config['label'] }}
</span>
