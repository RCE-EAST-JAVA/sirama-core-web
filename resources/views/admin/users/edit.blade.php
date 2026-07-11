<x-dashboard-layout title="Edit Akun Petugas" pageTitle="Edit Akun Petugas">

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            &larr; Kembali ke daftar
        </a>
    </div>

    <div class="max-w-xl bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}"
              x-data="{ role: '{{ $user->role }}' }">
            @csrf
            @method('PATCH')

            {{-- NIK --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">NIK <span class="text-red-500">*</span></label>
                <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" maxlength="16"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nik') border-red-400 @enderror">
                @error('nik')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- No WhatsApp --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $user->no_whatsapp) }}"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_whatsapp') border-red-400 @enderror">
                @error('no_whatsapp')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Role --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">Role <span class="text-red-500">*</span></label>
                <select name="role" x-model="role"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-400 @enderror">
                    <option value="admin_desa"      {{ old('role', $user->role) === 'admin_desa'      ? 'selected' : '' }}>Admin Desa</option>
                    <option value="admin_kecamatan" {{ old('role', $user->role) === 'admin_kecamatan' ? 'selected' : '' }}>Admin Kecamatan</option>
                </select>
                @error('role')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Desa (hanya jika admin_desa) --}}
            <div class="mb-4" x-show="role === 'admin_desa'" x-transition>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Desa <span class="text-red-500">*</span></label>
                <input type="text" name="desa" value="{{ old('desa', $user->desa) }}"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('desa') border-red-400 @enderror"
                    placeholder="Nama desa (contoh: Cibabat)">
                @error('desa')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password (opsional) --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">Password Baru <span class="text-gray-400">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror"
                    placeholder="Min. 8 karakter">
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-xs font-medium text-gray-600 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ulangi password baru">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">
                    Batal
                </a>
            </div>
        </form>
    </div>

</x-dashboard-layout>
