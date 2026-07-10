@php
    $corte = $this->getCorteData();
    $shift = $this->record;
@endphp

<x-filament::page>
    <div class="space-y-6">
        {{-- Info del turno --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Mesonero</p>
                    <p class="text-lg font-semibold text-espresso-800">{{ $shift->waiter->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Estado</p>
                    <p class="text-lg font-semibold">
                        @if ($shift->status === 'active')
                            <span class="text-green-600">Activo</span>
                        @elseif ($shift->status === 'completed')
                            <span class="text-gray-600">Completado</span>
                        @else
                            <span class="text-sienna-600">En pausa</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Inicio</p>
                    <p class="text-lg font-semibold text-espresso-800">{{ $shift->started_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Fin</p>
                    <p class="text-lg font-semibold text-espresso-800">{{ $shift->ended_at?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Duración</p>
                    <p class="text-lg font-semibold text-espresso-800">
                        @php
                            $hours = intdiv($corte['duration_minutes'], 60);
                            $mins = $corte['duration_minutes'] % 60;
                        @endphp
                        {{ $hours }}h {{ $mins }}m
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Descansos</p>
                    <p class="text-lg font-semibold text-espresso-800">{{ $shift->is_on_break ? 'En descanso' : 'Sin descansos' }}</p>
                </div>
            </div>
        </div>

        {{-- Resumen de corte --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-espresso-50 rounded-xl border border-espresso-200 p-4">
                <p class="text-sm text-espresso-600">Ventas totales</p>
                <p class="text-2xl font-bold text-espresso-800">${{ number_format($corte['total_sales'], 2) }}</p>
            </div>
            <div class="bg-cream-50 rounded-xl border border-cream-200 p-4">
                <p class="text-sm text-cream-700">Pedidos tomados</p>
                <p class="text-2xl font-bold text-espresso-800">{{ $corte['order_count'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-sm text-gray-500">Mesas atendidas</p>
                <p class="text-2xl font-bold text-espresso-800">{{ $corte['tables_served'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-sm text-gray-500">Promedio por pedido</p>
                <p class="text-2xl font-bold text-espresso-800">${{ number_format($corte['avg_per_order'], 2) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-sm text-gray-500">Promedio por mesa</p>
                <p class="text-2xl font-bold text-espresso-800">${{ number_format($corte['avg_per_table'], 2) }}</p>
            </div>
        </div>

        {{-- Pedidos del turno --}}
        @if ($corte['orders']->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Pedidos del turno</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-left">
                            <tr>
                                <th class="px-4 py-2 text-gray-500 font-medium">#</th>
                                <th class="px-4 py-2 text-gray-500 font-medium">Mesa</th>
                                <th class="px-4 py-2 text-gray-500 font-medium">Items</th>
                                <th class="px-4 py-2 text-gray-500 font-medium">Total</th>
                                <th class="px-4 py-2 text-gray-500 font-medium">Estado</th>
                                <th class="px-4 py-2 text-gray-500 font-medium">Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($corte['orders'] as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 font-medium text-espresso-800">#{{ $order->id }}</td>
                                    <td class="px-4 py-2">{{ $order->table->number }}</td>
                                    <td class="px-4 py-2">{{ $order->items->count() }}</td>
                                    <td class="px-4 py-2 font-medium">${{ number_format($order->total, 2) }}</td>
                                    <td class="px-4 py-2">
                                        <span class="text-xs px-2 py-0.5 rounded-full
                                            @if ($order->status === 'served') bg-green-100 text-green-700
                                            @elseif ($order->status === 'cancelled') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-600
                                            @endif
                                        ">
                                            {{ match($order->status) { 'pending' => 'Pendiente', 'preparing' => 'Preparando', 'ready' => 'Listo', 'served' => 'Servido', 'cancelled' => 'Cancelado', default => $order->status } }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-gray-500">{{ $order->created_at->format('H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-semibold">
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-right">Total</td>
                                <td class="px-4 py-2">${{ number_format($corte['total_sales'], 2) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <p class="text-gray-400">No hubo pedidos durante este turno.</p>
            </div>
        @endif
    </div>
</x-filament::page>
