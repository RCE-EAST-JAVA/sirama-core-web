{{-- Form Spesifik KK Perbaikan --}}
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Data Perbaikan KK</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Perbaikan <span class="text-red-500">*</span></label>
            <select name="jenis_perbaikan_id"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('jenis_perbaikan_id') border-red-400 @enderror">
                <option value="">-- Pilih Jenis Perbaikan --</option>
                @foreach($jenisPerbaikanList as $item)
                    <option value="{{ $item->id }}" {{ old('jenis_perbaikan_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_perbaikan }}
                    </option>
                @endforeach
            </select>
            @error('jenis_perbaikan_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

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
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Anggota yang Diperbaiki <span class="text-red-500">*</span></label>
            <input type="text" name="nama_anggota_yang_diperbaiki" value="{{ old('nama_anggota_yang_diperbaiki') }}"
                class="w-full text-base border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama_anggota_yang_diperbaiki') border-red-400 @enderror">
            @error('nama_anggota_yang_diperbaiki') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2" x-data="dataPerbaikan()">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Data Perbaikan <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-500 mb-3">Tambahkan pasangan data lama dan data baru yang perlu diperbaiki.</p>

            <template x-for="(item, index) in items" :key="index">
                <div class="flex gap-2 mb-2">
                    <input type="text" :name="'data_perbaikan[' + item.key + ']'" x-model="item.value"
                        :placeholder="item.key || 'Nilai baru'"
                        class="flex-1 text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <input type="text" x-model="item.key" placeholder="Nama field (contoh: nama_baru)"
                        class="flex-1 text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <button type="button" @click="remove(index)"
                        class="text-red-500 hover:text-red-700 px-2 text-lg leading-none">&times;</button>
                </div>
            </template>

            <button type="button" @click="add()"
                class="mt-1 text-sm text-brand-600 hover:text-brand-700 font-medium">+ Tambah Baris</button>

            @error('data_perbaikan') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-5">Upload Dokumen Pendukung <span class="text-sm font-normal text-gray-500">(jpg, png, pdf — maks. 5MB, bisa lebih dari satu)</span></h3>
    <div>
        <input type="file" name="file_pendukung[]" accept=".jpg,.jpeg,.png,.pdf" multiple
            class="w-full text-sm text-gray-500 file:mr-3 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 file:border-0 file:rounded-lg file:px-3 file:py-1.5 hover:file:bg-brand-100 cursor-pointer @error('file_pendukung') border border-red-400 rounded-lg p-1 @enderror">
        @error('file_pendukung') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>
</div>

@push('scripts')
<script>
function dataPerbaikan() {
    return {
        items: [{ key: '', value: '' }],
        add() { this.items.push({ key: '', value: '' }); },
        remove(i) { if (this.items.length > 1) this.items.splice(i, 1); }
    }
}
</script>
@endpush
