@props(['priority' => 'normal'])

@php
    $classes = match ($priority) {
        'vip' => 'bg-purple-100 text-purple-700',
        'urgent' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-600',
    };
    $label = match ($priority) {
        'vip' => 'VIP',
        'urgent' => 'Urgente',
        default => 'Normal',
    };
@endphp

<span class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded-full {{ $classes }}">
    {{ $label }}
</span>
