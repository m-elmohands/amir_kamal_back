@props(['name'])
<svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8', 'aria-hidden' => 'true']) }}>
    @switch($name)
        @case('dashboard') <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h6.5v6.5h-6.5v-6.5Zm10 0h6.5v6.5h-6.5v-6.5Zm-10 10h6.5v6.5h-6.5v-6.5Zm10 0h6.5v6.5h-6.5v-6.5Z"/> @break
        @case('products') <path stroke-linecap="round" stroke-linejoin="round" d="m21 8.25-9-4.5-9 4.5m18 0-9 4.5m9-4.5v7.5l-9 4.5m0-7.5-9-4.5m9 4.5v7.5m-9-12v7.5l9 4.5"/> @break
        @case('categories') <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/> @break
        @case('brands') <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.75H6A2.25 2.25 0 0 0 3.75 6v3.75L14.25 20.25l6-6L9.75 3.75Zm-3 3h.008v.008H6.75V6.75Z"/> @break
        @case('inquiries') <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 12a9.75 9.75 0 1 1-4.35-8.11M21.75 3.75v6h-6M8.25 12h7.5m-7.5 3h4.5"/> @break
        @case('settings') <path stroke-linecap="round" stroke-linejoin="round" d="M9.6 3.75h4.8l.6 2.1 2.05 1.2 2.12-.55 2.4 4.15-1.52 1.55v2.4l1.52 1.55-2.4 4.15-2.12-.55L15 20.95l-.6 2.1H9.6l-.6-2.1-2.05-1.2-2.12.55-2.4-4.15 1.52-1.55v-2.4L2.43 10.65 4.83 6.5l2.12.55L9 5.85l.6-2.1Z"/><circle cx="12" cy="13.4" r="3"/> @break
        @case('menu') <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/> @break
    @endswitch
</svg>
