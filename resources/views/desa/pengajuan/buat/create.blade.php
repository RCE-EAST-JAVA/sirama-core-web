@php
    $judulJenis = match($jenis) {
        'kia'            => 'KIA (Kartu Identitas Anak)',
        '3_in_1'         => '3 in 1 (Akta + KK + KIA)',
        'kk_penambahan'  => 'Penambahan Anggota KK',
        'kk_pengurangan' => 'Pengurangan Anggota KK',
        'kk_perbaikan'   => 'Perbaikan Data KK',
        'akta_kelahiran' => 'Akta Kelahiran',
        'akta_kematian'  => 'Akta Kematian',
        'kk_baru'        => 'Pembuatan KK Baru / Pisah KK',
        default          => $jenis,
    };
@endphp

<x-dashboard-layout title="Buat Pengajuan {{ $judulJenis }}" :pageTitle="'Buat Pengajuan: ' . $judulJenis">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('desa.pengajuan.buat.index') }}" class="text-base text-brand-600 hover:text-brand-700 font-medium">
            &larr; Pilih Jenis Layanan
        </a>
    </div>

    <form method="POST"
          action="{{ route('desa.pengajuan.buat.store', $jenis) }}"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf

        {{-- ================================================================ --}}
        {{-- SECTION 1: DATA IDENTITAS PEMOHON --}}
        {{-- ================================================================ --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-5">Data Identitas Pemohon</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                        class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_lengkap') border-red-400 @enderror">
                    @error('nama_lengkap') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" placeholder="16 digit"
                        class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nik') border-red-400 @enderror">
                    @error('nik') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}" placeholder="08xxxxxxxxxx"
                        class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('no_whatsapp') border-red-400 @enderror">
                    @error('no_whatsapp') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                        class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('tanggal_lahir') border-red-400 @enderror">
                    @error('tanggal_lahir') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin"
                        class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('jenis_kelamin') border-red-400 @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan <span class="text-red-500">*</span></label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
                        class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('pekerjaan') border-red-400 @enderror">
                    @error('pekerjaan') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="2"
                        class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('alamat') border-red-400 @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">RT <span class="text-red-500">*</span></label>
                    <input type="text" name="rt" value="{{ old('rt') }}" placeholder="001"
                        class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('rt') border-red-400 @enderror">
                    @error('rt') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">RW <span class="text-red-500">*</span></label>
                    <input type="text" name="rw" value="{{ old('rw') }}" placeholder="001"
                        class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('rw') border-red-400 @enderror">
                    @error('rw') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- ================================================================ --}}
        {{-- SECTION 2 & 3: FORM SPESIFIK + DOKUMEN (per jenis layanan) --}}
        {{-- ================================================================ --}}
        @switch($jenis)

            @case('kia')
                @include('desa.pengajuan.buat.partials.form-kia')
                @break

            @case('3_in_1')
                @include('desa.pengajuan.buat.partials.form-3in1')
                @break

            @case('kk_penambahan')
                @include('desa.pengajuan.buat.partials.form-kk-penambahan')
                @break

            @case('kk_pengurangan')
                @include('desa.pengajuan.buat.partials.form-kk-pengurangan')
                @break

            @case('kk_perbaikan')
                @include('desa.pengajuan.buat.partials.form-kk-perbaikan')
                @break

            @case('akta_kelahiran')
                @include('desa.pengajuan.buat.partials.form-akta-lahir')
                @break

            @case('akta_kematian')
                @include('desa.pengajuan.buat.partials.form-akta-kematian')
                @break

            @case('kk_baru')
                @include('desa.pengajuan.buat.partials.form-kk-baru')
                @break

        @endswitch

        {{-- ================================================================ --}}
        {{-- TOMBOL AKSI --}}
        {{-- ================================================================ --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="bg-brand-600 text-white text-base font-semibold px-6 py-3 rounded-lg hover:bg-brand-700 transition-colors">
                Buat Pengajuan
            </button>
            <a href="{{ route('desa.pengajuan.buat.index') }}"
                class="text-base text-gray-600 hover:text-gray-800 px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>

    </form>

</x-dashboard-layout>
