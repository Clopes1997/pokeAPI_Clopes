@props(['active'])

<a {{ $attributes->merge(['class' => ($active ?? false) ? 'nav-mobile-link is-active' : 'nav-mobile-link']) }}>
    {{ $slot }}
</a>
