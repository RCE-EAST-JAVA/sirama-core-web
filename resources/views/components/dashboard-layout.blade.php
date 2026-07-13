@props(['title' => 'Dashboard', 'pageTitle' => null])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - {{ config('app.name', 'SIRAMA') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800 text-base" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar Overlay (mobile) --}}
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black/40 lg:hidden"
            style="display: none;">
        </div>

        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0">

            {{-- Logo --}}
            <div class="flex items-center h-16 px-5 border-b border-gray-200 shrink-0 gap-3">
                <img src="{{ asset('assets/sirama-logo.png') }}" alt="SIRAMA Logo" class="h-9 w-auto">
                <div>
                    <span class="text-base font-bold text-gray-900 tracking-tight leading-tight block">SIRAMA</span>
                    <span class="text-xs font-medium text-brand-600 uppercase tracking-wider leading-tight block">Admin</span>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                @include('layouts.partials.sidebar')
            </nav>

            {{-- User Info + Logout --}}
            <div class="px-4 py-4 border-t border-gray-200 shrink-0">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center text-brand-700 font-bold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500 truncate">
                            @switch(auth()->user()->role)
                                @case('admin_aplikasi') Administrator @break
                                @case('admin_desa') Admin Desa @break
                                @case('admin_kecamatan') Admin Kecamatan @break
                            @endswitch
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:text-brand-700 hover:bg-brand-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @include('layouts.partials.navbar', ['pageTitle' => $pageTitle])

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="mx-6 mt-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 text-base rounded-lg flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-600 hover:text-green-800 ml-4 text-lg leading-none">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 text-base rounded-lg flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-600 hover:text-red-800 ml-4 text-lg leading-none">&times;</button>
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto px-6 py-6">
                {{ $slot }}
            </main>
        </div>
    </div>

</body>
</html>
