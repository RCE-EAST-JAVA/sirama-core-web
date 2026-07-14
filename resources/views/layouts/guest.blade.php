<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIRAMA') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-800">

    <div class="min-h-screen flex">

        {{-- Left Panel --}}
        <div class="hidden lg:flex lg:w-1/2 bg-brand-600 flex-col justify-between p-12 relative overflow-hidden">
            {{-- Background decoration --}}
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-500 rounded-full opacity-40"></div>
                <div class="absolute -bottom-32 -left-16 w-80 h-80 bg-brand-700 rounded-full opacity-40"></div>
                <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-brand-500 rounded-full opacity-20"></div>
            </div>

            {{-- Logo & Name --}}
            <div class="relative flex items-center gap-3">
                <img src="{{ asset('assets/Sirama-logo.png') }}" alt="SIRAMA Logo" class="h-10 w-auto">
                <div>
                    <span class="text-white text-xl font-bold tracking-tight leading-tight block">SIRAMA</span>
                    <span class="text-brand-200 text-xs font-medium uppercase tracking-wider leading-tight block">Sistem
                        Informasi Manajemen Administrasi | Kecamatan Sukosari</span>
                </div>
            </div>

            {{-- Main Text --}}
            <div class="relative">
                <h1 class="text-white text-4xl font-bold leading-tight mb-4">
                    Layanan Administrasi<br>Kependudukan Digital
                </h1>
                <p class="text-brand-100 text-base leading-relaxed max-w-sm">
                    Proses pengajuan dokumen kependudukan lebih mudah, cepat, dan transparan untuk masyarakat Kecamatan
                    Sukosari.
                </p>
            </div>

            {{-- Features --}}
            <div class="relative space-y-3">
                @foreach ([['icon' => '📄', 'text' => 'Pengajuan KIA, KK, dan Akta secara online'], ['icon' => '🔍', 'text' => 'Pantau status pengajuan secara real-time'], ['icon' => '✅', 'text' => 'Verifikasi dokumen lebih cepat dan akurat']] as $item)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-sm shrink-0">
                            {{ $item['icon'] }}
                        </div>
                        <span class="text-brand-100 text-sm">{{ $item['text'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">

                {{-- Mobile Logo --}}
                <div class="flex lg:hidden items-center justify-center gap-3 mb-8">
                    <img src="{{ asset('assets/Sirama-logo.png') }}" alt="SIRAMA Logo" class="h-10 w-auto">
                    <div>
                        <span class="text-gray-900 text-xl font-bold tracking-tight leading-tight block">SIRAMA</span>
                        <span
                            class="text-brand-600 text-xs font-medium uppercase tracking-wider leading-tight block">Sistem
                            Informasi Administrasi</span>
                    </div>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>

</body>

</html>
