@props(['status' => 'pending'])

@php
    $classes = match ($status) {
        'ready' => 'bg-green-100 text-green-700',
        'served' => 'bg-blue-100 text-blue-700',
        'pending' => 'bg-amber-100 text-amber-700',
        'preparing' => 'bg-purple-100 text-purple-700',
        'paid' => 'bg-gray-100 text-gray-500',
        default => 'bg-gray-100 text-gray-600',
    };
    $label = match ($status) {
        'ready' => 'Listo',
        'served' => 'Servido',
        'pending' => 'Pendiente',
        'preparing' => 'Preparando',
        'paid' => 'Pagado',
        default => $status,
    };
@endphp

<span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full {{ $classes }}">
    {{ $label }}
</span>
