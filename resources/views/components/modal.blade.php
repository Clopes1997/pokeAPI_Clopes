@props(['name'])

<div id="{{ $name }}" class="modal-overlay">
    <div class="modal">
        <div class="modal-body">
            {{ $slot }}
        </div>
    </div>
</div>
