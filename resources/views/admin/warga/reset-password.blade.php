<x-dashboard-layout title="Reset Password Warga" pageTitle="Reset Password Warga">

    <div class="mb-6">
        <a href="{{ route('admin.warga.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            &larr; Kembali ke daftar
        </a>
    </div>

    <div class="max-w-xl bg-white rounded-xl border border-gray-200 p-6">
        {{-- Info Warga --}}
        <div class="mb-6 pb-6 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Warga</h3>
            <div class="space-y-2">
                <div class="flex">
                    <span class="text-xs text-gray-500 w-20">NIK:</span>
                    <span class="text-sm font-mono text-gray-900">{{ $user->nik }}</span>
                </div>
                <div class="flex">
                    <span class="text-xs text-gray-500 w-20">Nama:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                </div>
            </div>
        </div>

        {{-- Warning --}}
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                <strong>Perhatian:</strong> Password lama warga akan diganti dengan password baru. Pastikan Anda memberitahu password baru kepada warga.
            </p>
        </div>

        {{-- Form Reset Password --}}
        <form method="POST" action="{{ route('admin.warga.reset.password', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-600 mb-1">Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror"
                    placeholder="Min. 8 karakter" required>
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-xs font-medium text-gray-600 mb-1">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password_confirmation') border-red-400 @enderror"
                    placeholder="Ulangi password baru" required>
                @error('password_confirmation')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors">
                    Reset Password
                </button>
                <a href="{{ route('admin.warga.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">
                    Batal
                </a>
            </div>
        </form>
    </div>

</x-dashboard-layout>
