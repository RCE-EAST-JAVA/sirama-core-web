@php $role = auth()->user()->role; @endphp

{{-- Admin Aplikasi --}}
@if($role === 'admin_aplikasi')
    <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </x-slot>
        Dashboard
    </x-sidebar-link>

    <div class="pt-5 pb-2">
        <p class="px-3 text-sm font-semibold text-gray-500 uppercase tracking-wider">Manajemen</p>
    </div>

    <x-sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </x-slot>
        Kelola Admin
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('admin.warga.index') }}" :active="request()->routeIs('admin.warga.*')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </x-slot>
        Kelola Warga
    </x-sidebar-link>

    <div class="pt-5 pb-2">
        <p class="px-3 text-sm font-semibold text-gray-500 uppercase tracking-wider">Laporan</p>
    </div>

    <x-sidebar-link href="{{ route('admin.riwayat') }}" :active="request()->routeIs('admin.riwayat')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-slot>
        Riwayat Pengajuan
    </x-sidebar-link>

    <div class="pt-5 pb-2">
        <p class="px-3 text-sm font-semibold text-gray-500 uppercase tracking-wider">Sistem</p>
    </div>

    <x-sidebar-link href="{{ url('/api/documentation') }}" :active="false">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </x-slot>
        Dashboard
    </x-sidebar-link>

    <div class="pt-5 pb-2">
        <p class="px-3 text-sm font-semibold text-gray-500 uppercase tracking-wider">Manajemen</p>
    </div>

    <x-sidebar-link href="{{ route('admin.warga.index') }}" :active="request()->routeIs('admin.warga.*')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </x-slot>
        Kelola Warga
    </x-sidebar-link>

    <div class="pt-5 pb-2">
        <p class="px-3 text-sm font-semibold text-gray-500 uppercase tracking-wider">Pengajuan</p>
    </div>

    <x-sidebar-link href="{{ route('desa.pengajuan.index') }}" :active="request()->routeIs('desa.pengajuan.show', 'desa.pengajuan.index')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </x-slot>
        Daftar Pengajuan
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('desa.pengajuan.buat.index') }}" :active="request()->routeIs('desa.pengajuan.buat.*')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </x-slot>
        Buat Pengajuan
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('desa.riwayat') }}" :active="request()->routeIs('desa.riwayat')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-slot>
        Riwayat Pengajuan
    </x-sidebar-link>
@endif

{{-- Admin Kecamatan --}}
@if($role === 'admin_kecamatan')
    <x-sidebar-link href="{{ route('kecamatan.dashboard') }}" :active="request()->routeIs('kecamatan.dashboard')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </x-slot>
        Dashboard
    </x-sidebar-link>

    <div class="pt-5 pb-2">
        <p class="px-3 text-sm font-semibold text-gray-500 uppercase tracking-wider">Manajemen</p>
    </div>

    <x-sidebar-link href="{{ route('admin.warga.index') }}" :active="request()->routeIs('admin.warga.*')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </x-slot>
        Kelola Warga
    </x-sidebar-link>

    <div class="pt-5 pb-2">
        <p class="px-3 text-sm font-semibold text-gray-500 uppercase tracking-wider">Pengajuan</p>
    </div>

    <x-sidebar-link href="{{ route('kecamatan.pengajuan.index') }}" :active="request()->routeIs('kecamatan.pengajuan.*')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </x-slot>
        Daftar Pengajuan
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('kecamatan.riwayat') }}" :active="request()->routeIs('kecamatan.riwayat')">
        <x-slot name="icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-slot>
        Riwayat Pengajuan
    </x-sidebar-link>
@endif
