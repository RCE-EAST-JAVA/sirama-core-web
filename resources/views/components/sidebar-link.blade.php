@props(['href', 'active' => false])

<a href="{{ $href }}"
    class="flex items-center gap-3 px-3 py-2.5 text-base rounded-lg transition-colors
        {{ $active
            ? 'bg-brand-50 text-brand-700 font-semibold border-l-4 border-brand-500'
            : 'text-gray-700 hover:bg-brand-50 hover:text-brand-700' }}">

    @isset($icon)
        <span class="{{ $active ? 'text-brand-600' : 'text-gray-500' }}">
            {{ $icon }}
        </span>
    @endisset

    {{ $slot }}
</a>
