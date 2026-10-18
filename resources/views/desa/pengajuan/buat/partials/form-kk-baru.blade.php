{{-- Form Spesifik KK Baru / Pisah KK --}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Data KK Lama & Lokasi Baru</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kepala Keluarga <span class="text-red-500">*</span></label>
            <input type="text" name="nama_kepala_keluarga" value="{{ old('nama_kepala_keluarga') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_kepala_keluarga') border-red-400 @enderror">
            @error('nama_kepala_keluarga') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor KK Lama <span class="text-red-500">*</span></label>
            <input type="text" name="nomor_kk_lama" value="{{ old('nomor_kk_lama') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nomor_kk_lama') border-red-400 @enderror">
            @error('nomor_kk_lama') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Ketua RT <span class="text-red-500">*</span></label>
            <input type="text" name="nama_ketua_rt" value="{{ old('nama_ketua_rt') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_ketua_rt') border-red-400 @enderror">
            @error('nama_ketua_rt') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Ketua RW <span class="text-red-500">*</span></label>
            <input type="text" name="nama_ketua_rw" value="{{ old('nama_ketua_rw') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_ketua_rw') border-red-400 @enderror">
            @error('nama_ketua_rw') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Baru <span class="text-red-500">*</span></label>
            <textarea name="alamat_baru" rows="2"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('alamat_baru') border-red-400 @enderror">{{ old('alamat_baru') }}</textarea>
            @error('alamat_baru') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">RT (KK Baru) <span class="text-red-500">*</span></label>
            <input type="text" name="kk_rt" value="{{ old('kk_rt') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('kk_rt') border-red-400 @enderror">
            @error('kk_rt') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">RW (KK Baru) <span class="text-red-500">*</span></label>
            <input type="text" name="kk_rw" value="{{ old('kk_rw') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('kk_rw') border-red-400 @enderror">
            @error('kk_rw') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Desa (KK Baru) <span class="text-red-500">*</span></label>
            <input type="text" name="kk_desa" value="{{ old('kk_desa', $desa) }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('kk_desa') border-red-400 @enderror">
            @error('kk_desa') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Alasan Pembuatan KK Baru <span class="text-red-500">*</span></label>
            <input type="text" name="alasan_pembuatan" value="{{ old('alasan_pembuatan') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('alasan_pembuatan') border-red-400 @enderror">
            @error('alasan_pembuatan') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Data Suami</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap Suami <span class="text-red-500">*</span></label>
            <input type="text" name="nama_lengkap_suami" value="{{ old('nama_lengkap_suami') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_lengkap_suami') border-red-400 @enderror">
            @error('nama_lengkap_suami') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tempat Lahir Suami <span class="text-red-500">*</span></label>
            <input type="text" name="tempat_lahir_suami" value="{{ old('tempat_lahir_suami') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('tempat_lahir_suami') border-red-400 @enderror">
            @error('tempat_lahir_suami') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir Suami <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_lahir_suami" value="{{ old('tanggal_lahir_suami') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('tanggal_lahir_suami') border-red-400 @enderror">
            @error('tanggal_lahir_suami') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Agama Suami <span class="text-red-500">*</span></label>
            <input type="text" name="agama_suami" value="{{ old('agama_suami') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('agama_suami') border-red-400 @enderror">
            @error('agama_suami') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan Suami <span class="text-red-500">*</span></label>
            <input type="text" name="pekerjaan_suami" value="{{ old('pekerjaan_suami') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('pekerjaan_suami') border-red-400 @enderror">
            @error('pekerjaan_suami') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Data Istri</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap Istri <span class="text-red-500">*</span></label>
            <input type="text" name="nama_lengkap_istri" value="{{ old('nama_lengkap_istri') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_lengkap_istri') border-red-400 @enderror">
            @error('nama_lengkap_istri') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tempat Lahir Istri <span class="text-red-500">*</span></label>
            <input type="text" name="tempat_lahir_istri" value="{{ old('tempat_lahir_istri') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('tempat_lahir_istri') border-red-400 @enderror">
            @error('tempat_lahir_istri') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir Istri <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_lahir_istri" value="{{ old('tanggal_lahir_istri') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('tanggal_lahir_istri') border-red-400 @enderror">
            @error('tanggal_lahir_istri') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Agama Istri <span class="text-red-500">*</span></label>
            <input type="text" name="agama_istri" value="{{ old('agama_istri') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('agama_istri') border-red-400 @enderror">
            @error('agama_istri') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan Istri <span class="text-red-500">*</span></label>
            <input type="text" name="pekerjaan_istri" value="{{ old('pekerjaan_istri') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('pekerjaan_istri') border-red-400 @enderror">
            @error('pekerjaan_istri') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Upload Dokumen <span class="text-sm font-normal text-gray-500">(jpg, png, pdf — maks. 5MB)</span></h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach([
            'file_kk_lama'        => 'KK Lama',
            'file_ktp_suami'      => 'KTP Suami',
            'file_ktp_istri'      => 'KTP Istri',
            'file_surat_domisili' => 'Surat Domisili',
            'file_surat_nikah'    => 'Surat/Buku Nikah',
            'file_ijazah_suami'   => 'Ijazah Terakhir Suami',
            'file_ijazah_istri'   => 'Ijazah Terakhir Istri',
        ] as $field => $label)
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }} <span class="text-red-500">*</span></label>
            <input type="file" name="{{ $field }}" accept=".jpg,.jpeg,.png,.pdf"
                class="w-full text-sm text-gray-500 file:mr-3 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 file:border-0 file:rounded-lg file:px-3 file:py-1.5 hover:file:bg-brand-100 cursor-pointer @error($field) border border-red-400 rounded-lg p-1 @enderror">
            @error($field) <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        @endforeach
    </div>
</div>
