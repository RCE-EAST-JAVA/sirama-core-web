<x-dashboard-layout title="Buat Pengajuan" pageTitle="Buat Pengajuan untuk Warga">

    <div class="mb-6">
        <p class="text-sm text-gray-500">Pilih jenis layanan yang akan diajukan. Data identitas pemohon akan diisi secara manual.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- KIA --}}
        <a href="{{ route('desa.pengajuan.buat.create', 'kia') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-brand-400 hover:shadow-sm transition-all group">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">KIA</h3>
            <p class="text-sm text-gray-500">Kartu Identitas Anak</p>
        </a>

        {{-- 3 in 1 --}}
        <a href="{{ route('desa.pengajuan.buat.create', '3_in_1') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-brand-400 hover:shadow-sm transition-all group">
            <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center mb-4 group-hover:bg-purple-100 transition-colors">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">3 in 1</h3>
            <p class="text-sm text-gray-500">Akta Kelahiran + KK + KIA</p>
        </a>

        {{-- KK Penambahan --}}
        <a href="{{ route('desa.pengajuan.buat.create', 'kk_penambahan') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-brand-400 hover:shadow-sm transition-all group">
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center mb-4 group-hover:bg-green-100 transition-colors">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">KK Penambahan</h3>
            <p class="text-sm text-gray-500">Penambahan anggota KK</p>
        </a>

        {{-- KK Pengurangan --}}
        <a href="{{ route('desa.pengajuan.buat.create', 'kk_pengurangan') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-brand-400 hover:shadow-sm transition-all group">
            <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center mb-4 group-hover:bg-orange-100 transition-colors">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">KK Pengurangan</h3>
            <p class="text-sm text-gray-500">Pengurangan anggota KK</p>
        </a>

        {{-- KK Perbaikan --}}
        <a href="{{ route('desa.pengajuan.buat.create', 'kk_perbaikan') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-brand-400 hover:shadow-sm transition-all group">
            <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center mb-4 group-hover:bg-yellow-100 transition-colors">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">KK Perbaikan</h3>
            <p class="text-sm text-gray-500">Perbaikan data KK</p>
        </a>

        {{-- Akta Kelahiran --}}
        <a href="{{ route('desa.pengajuan.buat.create', 'akta_kelahiran') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-brand-400 hover:shadow-sm transition-all group">
            <div class="w-10 h-10 rounded-lg bg-teal-50 flex items-center justify-center mb-4 group-hover:bg-teal-100 transition-colors">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">Akta Kelahiran</h3>
            <p class="text-sm text-gray-500">Penerbitan akta kelahiran</p>
        </a>

        {{-- Akta Kematian --}}
        <a href="{{ route('desa.pengajuan.buat.create', 'akta_kematian') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-brand-400 hover:shadow-sm transition-all group">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center mb-4 group-hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">Akta Kematian</h3>
            <p class="text-sm text-gray-500">Penerbitan akta kematian</p>
        </a>

        {{-- KK Baru --}}
        <a href="{{ route('desa.pengajuan.buat.create', 'kk_baru') }}"
            class="bg-white rounded-xl border border-gray-200 p-5 hover:border-brand-400 hover:shadow-sm transition-all group">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center mb-4 group-hover:bg-indigo-100 transition-colors">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 mb-1">KK Baru / Pisah KK</h3>
            <p class="text-sm text-gray-500">Pembuatan KK baru</p>
        </a>

    </div>

</x-dashboard-layout>
