<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">

    {{-- Mobile: hamburger + page title --}}
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 rounded-md text-gray-500 hover:text-brand-600 hover:bg-brand-50 transition-colors"
            aria-label="Toggle sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        @isset($pageTitle)
            <h1 class="text-lg font-semibold text-gray-900">{{ $pageTitle }}</h1>
        @endisset
    </div>

    {{-- Right: notification bell --}}
    <div class="flex items-center gap-4">
        <x-notification-bell />
    </div>
</header>
