{{-- Form Spesifik Akta Kematian --}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Data Anggota yang Meninggal</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama_lengkap_anggota" value="{{ old('nama_lengkap_anggota') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_lengkap_anggota') border-red-400 @enderror">
            @error('nama_lengkap_anggota') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">NIK <span class="text-red-500">*</span></label>
            <input type="text" name="nik_anggota" value="{{ old('nik_anggota') }}" maxlength="16"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nik_anggota') border-red-400 @enderror">
            @error('nik_anggota') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
            <textarea name="alamat_lengkap_anggota" rows="2"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('alamat_lengkap_anggota') border-red-400 @enderror">{{ old('alamat_lengkap_anggota') }}</textarea>
            @error('alamat_lengkap_anggota') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Upload Dokumen <span class="text-sm font-normal text-gray-500">(jpg, png, pdf — maks. 5MB)</span></h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach(['file_kk_asli' => 'KK Asli', 'file_ktp_asli' => 'KTP Asli', 'file_sk_kematian' => 'Surat Keterangan Kematian'] as $field => $label)
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }} <span class="text-red-500">*</span></label>
            <input type="file" name="{{ $field }}" accept=".jpg,.jpeg,.png,.pdf"
                class="w-full text-sm text-gray-500 file:mr-3 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 file:border-0 file:rounded-lg file:px-3 file:py-1.5 hover:file:bg-brand-100 cursor-pointer @error($field) border border-red-400 rounded-lg p-1 @enderror">
            @error($field) <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        @endforeach
    </div>
</div>
