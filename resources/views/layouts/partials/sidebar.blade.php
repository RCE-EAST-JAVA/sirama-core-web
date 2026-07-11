@php $role = auth()->user()->role; @endphp

{{-- Admin Aplikasi --}}
@if($role === 'admin_aplikasi')
    <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
        <x-slot name="icon">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </x-slot>
        Dashboard
    </x-sidebar-link>

    <div class="pt-4 pb-1">
        <p class="px-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Manajemen</p>
    </div>

    <x-sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
        <x-slot name="icon">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </x-slot>
        Kelola Admin
    </x-sidebar-link>

    <div class="pt-4 pb-1">
        <p class="px-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Sistem</p>
    </div>

    <x-sidebar-link href="{{ url('/api/documentation') }}" :active="false">
        <x-slot name="icon">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </x-slot>
        API Documentation
    </x-sidebar-link>
@endif

{{-- Admin Desa --}}
@if($role === 'admin_desa')
    <x-sidebar-link href="{{ route('desa.dashboard') }}" :active="request()->routeIs('desa.dashboard')">
        <x-slot name="icon">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </x-slot>
        Dashboard
    </x-sidebar-link>

    <div class="pt-4 pb-1">
        <p class="px-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Pengajuan</p>
    </div>

    <x-sidebar-link href="{{ route('desa.pengajuan.index') }}" :active="request()->routeIs('desa.pengajuan.*')">
        <x-slot name="icon">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </x-slot>
        Daftar Pengajuan
    </x-sidebar-link>
@endif

{{-- Admin Kecamatan --}}
@if($role === 'admin_kecamatan')
    <x-sidebar-link href="{{ route('kecamatan.dashboard') }}" :active="request()->routeIs('kecamatan.dashboard')">
        <x-slot name="icon">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </x-slot>
        Dashboard
    </x-sidebar-link>

    <div class="pt-4 pb-1">
        <p class="px-3 text-xs font-medium text-gray-400 uppercase tracking-wider">Pengajuan</p>
    </div>

    <x-sidebar-link href="{{ route('kecamatan.pengajuan.index') }}" :active="request()->routeIs('kecamatan.pengajuan.*')">
        <x-slot name="icon">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </x-slot>
        Daftar Pengajuan
    </x-sidebar-link>
@endif
