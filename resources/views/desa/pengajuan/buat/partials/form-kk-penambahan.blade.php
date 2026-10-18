{{-- Form Spesifik KK Penambahan --}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Data Kartu Keluarga</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kepala Keluarga <span class="text-red-500">*</span></label>
            <input type="text" name="nama_kepala_keluarga" value="{{ old('nama_kepala_keluarga') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_kepala_keluarga') border-red-400 @enderror">
            @error('nama_kepala_keluarga') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor KK <span class="text-red-500">*</span></label>
            <input type="text" name="nomor_kk" value="{{ old('nomor_kk') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nomor_kk') border-red-400 @enderror">
            @error('nomor_kk') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
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

    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Data Anggota yang Ditambahkan</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama_lengkap_tambahan" value="{{ old('nama_lengkap_tambahan') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_lengkap_tambahan') border-red-400 @enderror">
            @error('nama_lengkap_tambahan') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
            <select name="jenis_kelamin_tambahan"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('jenis_kelamin_tambahan') border-red-400 @enderror">
                <option value="">-- Pilih --</option>
                <option value="L" {{ old('jenis_kelamin_tambahan') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin_tambahan') === 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin_tambahan') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tempat Lahir <span class="text-red-500">*</span></label>
            <input type="text" name="tempat_lahir_tambahan" value="{{ old('tempat_lahir_tambahan') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('tempat_lahir_tambahan') border-red-400 @enderror">
            @error('tempat_lahir_tambahan') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_lahir_tambahan" value="{{ old('tanggal_lahir_tambahan') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('tanggal_lahir_tambahan') border-red-400 @enderror">
            @error('tanggal_lahir_tambahan') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Status Hubungan <span class="text-red-500">*</span></label>
            <input type="text" name="status_hubungan" value="{{ old('status_hubungan') }}" placeholder="Anak, Orang Tua, dll."
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('status_hubungan') border-red-400 @enderror">
            @error('status_hubungan') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Agama <span class="text-red-500">*</span></label>
            <input type="text" name="agama" value="{{ old('agama') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('agama') border-red-400 @enderror">
            @error('agama') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelainan Fisik/Mental <span class="text-red-500">*</span></label>
            <input type="text" name="kelainan_fisik_mental" value="{{ old('kelainan_fisik_mental', 'Tidak Ada') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('kelainan_fisik_mental') border-red-400 @enderror">
            @error('kelainan_fisik_mental') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Penyandang Cacat <span class="text-red-500">*</span></label>
            <input type="text" name="penyandang_cacat" value="{{ old('penyandang_cacat', 'Tidak') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('penyandang_cacat') border-red-400 @enderror">
            @error('penyandang_cacat') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Ibu Kandung <span class="text-red-500">*</span></label>
            <input type="text" name="nama_ibu_kandung" value="{{ old('nama_ibu_kandung') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_ibu_kandung') border-red-400 @enderror">
            @error('nama_ibu_kandung') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">NIK Ibu <span class="text-red-500">*</span></label>
            <input type="text" name="nik_ibu" value="{{ old('nik_ibu') }}" maxlength="16"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nik_ibu') border-red-400 @enderror">
            @error('nik_ibu') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Ayah Kandung <span class="text-red-500">*</span></label>
            <input type="text" name="nama_ayah_kandung" value="{{ old('nama_ayah_kandung') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_ayah_kandung') border-red-400 @enderror">
            @error('nama_ayah_kandung') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">NIK Ayah <span class="text-red-500">*</span></label>
            <input type="text" name="nik_ayah" value="{{ old('nik_ayah') }}" maxlength="16"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nik_ayah') border-red-400 @enderror">
            @error('nik_ayah') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Upload Dokumen <span class="text-sm font-normal text-gray-500">(jpg, png, pdf — maks. 5MB)</span></h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach(['file_kk_asli' => 'KK Asli', 'file_sk_lahir_akta' => 'Surat Keterangan Lahir / Akta', 'file_ktp_suami_istri' => 'KTP Suami/Istri', 'file_surat_nikah' => 'Surat/Buku Nikah'] as $field => $label)
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }} <span class="text-red-500">*</span></label>
            <input type="file" name="{{ $field }}" accept=".jpg,.jpeg,.png,.pdf"
                class="w-full text-sm text-gray-500 file:mr-3 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 file:border-0 file:rounded-lg file:px-3 file:py-1.5 hover:file:bg-brand-100 cursor-pointer @error($field) border border-red-400 rounded-lg p-1 @enderror">
            @error($field) <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        @endforeach
    </div>
</div>
