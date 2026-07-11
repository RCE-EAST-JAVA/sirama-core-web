@props(['href', 'active' => false])

<a href="{{ $href }}"
    class="flex items-center gap-3 px-3 py-2 text-sm rounded-md transition-colors
        {{ $active
            ? 'bg-gray-100 text-gray-900 font-medium'
            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">

    @isset($icon)
        <span class="{{ $active ? 'text-gray-700' : 'text-gray-400' }}">
            {{ $icon }}
        </span>
    @endisset

    {{ $slot }}
</a>
