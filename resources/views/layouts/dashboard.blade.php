<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'SIRAMA') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800" x-data="{ sidebarOpen: false }">

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
            <div class="flex items-center h-16 px-6 border-b border-gray-200 shrink-0">
                <span class="text-lg font-semibold text-gray-900 tracking-tight">SIRAMA</span>
                <span class="ml-2 text-xs font-medium text-gray-400 uppercase tracking-wider">Admin</span>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                @include('layouts.partials.sidebar')
            </nav>

            {{-- User Info --}}
            <div class="px-4 py-3 border-t border-gray-200 shrink-0">
                <div class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</div>
                <div class="text-xs text-gray-500 truncate mt-0.5">
                    @switch(auth()->user()->role)
                        @case('admin_aplikasi') Administrator @break
                        @case('admin_desa') Admin Desa {{ auth()->user()->desa }} @break
                        @case('admin_kecamatan') Admin Kecamatan @break
                        @default {{ auth()->user()->role }}
                    @endswitch
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="text-xs text-gray-500 hover:text-red-500 transition-colors">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            {{-- Navbar --}}
            @include('layouts.partials.navbar')

            {{-- Flash Messages --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="mx-6 mt-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-600 hover:text-green-800 ml-4">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="mx-6 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-600 hover:text-red-800 ml-4">&times;</button>
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
