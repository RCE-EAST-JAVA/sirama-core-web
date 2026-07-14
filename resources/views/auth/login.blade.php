<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Selamat Datang</h1>
        <p class="text-gray-500 text-sm">Masuk ke akun SIRAMA Anda</p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- NIK --}}
        <div>
            <label for="nik" class="block text-sm font-medium text-gray-700 mb-1.5">
                NIK
            </label>
            <input
                id="nik"
                type="text"
                name="nik"
                value="{{ old('nik') }}"
                required
                autofocus
                autocomplete="username"
                maxlength="16"
                placeholder="Masukkan 16 digit NIK"
                class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm text-base py-2.5 @error('nik') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
            >
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                Password
            </label>
            <div class="relative" x-data="{ show: false }">
                <input
                    id="password"
                    :type="show ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                    class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm text-base py-2.5 pr-11 @error('password') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                >
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 transition-colors"
                    tabindex="-1"
                >
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500"
            >
            <label for="remember_me" class="ms-2 text-sm text-gray-600">Ingat saya</label>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full inline-flex items-center justify-center px-5 py-3 bg-brand-600 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:bg-brand-700 focus:bg-brand-700 active:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
            Masuk ke SIRAMA
        </button>
    </form>

    {{-- Footer --}}
    <p class="mt-8 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} SIRAMA — Kecamatan Sukosari
    </p>
</x-guest-layout>
