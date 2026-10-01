@props(['active'])

<a {{ $attributes->merge(['class' => ($active ?? false) ? 'nav-link is-active' : 'nav-link']) }}>
    {{ $slot }}
</a>
