<x-dashboard-layout title="Tambah Akun Petugas" pageTitle="Tambah Akun Petugas">

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            &larr; Kembali ke daftar
        </a>
    </div>

    <div class="max-w-xl bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.users.store') }}" x-data="{ role: '' }">
            @csrf

            {{-- NIK --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">NIK <span class="text-red-500">*</span></label>
                <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nik') border-red-400 @enderror"
                    placeholder="16 digit NIK">
                @error('nik')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror"
                    placeholder="Nama lengkap petugas">
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- No WhatsApp --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_whatsapp') border-red-400 @enderror"
                    placeholder="08xxxxxxxxxx">
                @error('no_whatsapp')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Role --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">Role <span class="text-red-500">*</span></label>
                <select name="role" x-model="role"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-400 @enderror">
                    <option value="">Pilih role...</option>
                    <option value="admin_desa"      {{ old('role') === 'admin_desa'      ? 'selected' : '' }}>Admin Desa</option>
                    <option value="admin_kecamatan" {{ old('role') === 'admin_kecamatan' ? 'selected' : '' }}>Admin Kecamatan</option>
                </select>
                @error('role')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Desa (hanya jika admin_desa) --}}
            <div class="mb-4" x-show="role === 'admin_desa'" x-transition>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Desa <span class="text-red-500">*</span></label>
                <input type="text" name="desa" value="{{ old('desa') }}"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('desa') border-red-400 @enderror"
                    placeholder="Nama desa (contoh: Cibabat)">
                @error('desa')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror"
                    placeholder="Min. 8 karakter">
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-6">
                <label class="block text-xs font-medium text-gray-600 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ulangi password">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors">
                    Buat Akun
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">
                    Batal
                </a>
            </div>
        </form>
    </div>

</x-dashboard-layout>
