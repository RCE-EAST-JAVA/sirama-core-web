{{-- Form Spesifik 3 in 1 --}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Data Anak</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Anak <span class="text-red-500">*</span></label>
            <input type="text" name="nama_anak" value="{{ old('nama_anak') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_anak') border-red-400 @enderror">
            @error('nama_anak') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir Anak <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_lahir_anak" value="{{ old('tanggal_lahir_anak') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('tanggal_lahir_anak') border-red-400 @enderror">
            @error('tanggal_lahir_anak') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Upload Dokumen <span class="text-sm font-normal text-gray-500">(jpg, png, pdf — maks. 5MB)</span></h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach(['file_sk_lahir' => 'Surat Keterangan Lahir', 'file_kk' => 'Kartu Keluarga', 'file_ktp_ortu' => 'KTP Orang Tua', 'file_surat_nikah' => 'Surat/Buku Nikah', 'file_foto_anak' => 'Foto Anak'] as $field => $label)
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }} <span class="text-red-500">*</span></label>
            <input type="file" name="{{ $field }}" accept=".jpg,.jpeg,.png,.pdf"
                class="w-full text-sm text-gray-500 file:mr-3 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 file:border-0 file:rounded-lg file:px-3 file:py-1.5 hover:file:bg-brand-100 cursor-pointer @error($field) border border-red-400 rounded-lg p-1 @enderror">
            @error($field) <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        @endforeach
    </div>
</div>
