{{-- Form Spesifik KIA --}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Data Anak</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap Anak <span class="text-red-500">*</span></label>
            <input type="text" name="nama_lengkap_anak" value="{{ old('nama_lengkap_anak') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_lengkap_anak') border-red-400 @enderror">
            @error('nama_lengkap_anak') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tempat Lahir Anak <span class="text-red-500">*</span></label>
            <input type="text" name="tempat_lahir_anak" value="{{ old('tempat_lahir_anak') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('tempat_lahir_anak') border-red-400 @enderror">
            @error('tempat_lahir_anak') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir Anak <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_lahir_anak" value="{{ old('tanggal_lahir_anak') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('tanggal_lahir_anak') border-red-400 @enderror">
            @error('tanggal_lahir_anak') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin Anak <span class="text-red-500">*</span></label>
            <select name="jenis_kelamin_anak"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('jenis_kelamin_anak') border-red-400 @enderror">
                <option value="">-- Pilih --</option>
                <option value="L" {{ old('jenis_kelamin_anak') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin_anak') === 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin_anak') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kepala Keluarga <span class="text-red-500">*</span></label>
            <input type="text" name="nama_kepala_keluarga" value="{{ old('nama_kepala_keluarga') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_kepala_keluarga') border-red-400 @enderror">
            @error('nama_kepala_keluarga') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Agama <span class="text-red-500">*</span></label>
            <input type="text" name="agama" value="{{ old('agama') }}" placeholder="Islam, Kristen, dll."
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('agama') border-red-400 @enderror">
            @error('agama') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kewarganegaraan <span class="text-red-500">*</span></label>
            <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan', 'WNI') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('kewarganegaraan') border-red-400 @enderror">
            @error('kewarganegaraan') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

    </div>
</div>

{{-- Dokumen KIA --}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Upload Dokumen <span class="text-sm font-normal text-gray-500">(jpg, png, pdf — maks. 5MB)</span></h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach(['file_akta_kelahiran' => 'Akta Kelahiran', 'file_kk' => 'Kartu Keluarga', 'file_surat_nikah' => 'Surat/Buku Nikah', 'file_foto_anak' => 'Foto Anak'] as $field => $label)
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }} <span class="text-red-500">*</span></label>
            <input type="file" name="{{ $field }}" accept=".jpg,.jpeg,.png,.pdf"
                class="w-full text-sm text-gray-500 file:mr-3 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 file:border-0 file:rounded-lg file:px-3 file:py-1.5 hover:file:bg-brand-100 cursor-pointer @error($field) border border-red-400 rounded-lg p-1 @enderror">
            @error($field) <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        @endforeach
    </div>
</div>
