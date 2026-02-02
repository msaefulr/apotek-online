@props(['type' => 'success'])

@php
    $map = [
        'success' => 'border-green-200 bg-green-50 text-green-800',
        'danger' => 'border-rose-200 bg-rose-50 text-rose-800',
        'info' => 'border-blue-200 bg-blue-50 text-blue-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
    ];
@endphp

<div class="rounded-xl border px-4 py-3 shadow-sm {{ $map[$type] ?? $map['info'] }}">
    {{ $slot }}
</div>