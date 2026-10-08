@php
    $orders = $this->getOrdersData();
    $summary = $this->getSummary();
@endphp

<div x-data="{ closeOrderId: null, paymentMethod: '' }" class="space-y-6">
    {{-- Summary cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Listos por cobrar</p>
            <p class="text-2xl font-bold text-espresso-800">{{ $summary['ready'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Servidos</p>
            <p class="text-2xl font-bold text-espresso-800">{{ $summary['served'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-4">
            <p class="text-sm text-gray-500">Por cobrar total</p>
            <p class="text-2xl font-bold text-amber-600">${{ number_format($summary['total_pending'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-green-200 p-4">
            <p class="text-sm text-gray-500">Cobrado hoy</p>
            <p class="text-2xl font-bold text-green-600">${{ number_format($summary['today_closed'], 2) }}</p>
        </div>
    </div>

    {{-- Orders table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Cuentas activas</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse ($orders as $order)
                <div
                    x-data="{ open: false }"
                    class="{{ $order['status'] === 'ready' ? 'bg-green-50' : ($order['status'] === 'served' ? 'bg-blue-50' : '') }}"
                >
                    {{-- Order header --}}
                    <div
                        @click="open = !open"
                        class="flex items-center justify-between px-5 py-3 cursor-pointer hover:bg-gray-50 transition-colors"
                    >
                        <div class="flex items-center gap-4">
                            <span class="font-bold text-espresso-900 text-lg">Mesa {{ $order['table_number'] }}</span>
                            <span class="text-sm text-gray-500">{{ $order['zone'] }}</span>
                            <span class="text-sm text-gray-500">· {{ $order['waiter'] }}</span>
                            @if ($order['customer'])
                                <span class="text-sm text-gray-500">· {{ $order['customer'] }}</span>
                            @endif
                            <x-cajera-status-badge :status="$order['status']" />
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-400">{{ $order['created_at'] }}</span>
                            <span class="font-bold text-lg text-espresso-800">${{ number_format($order['total'], 2) }}</span>
                            <svg class="w-5 h-5 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    {{-- Order details --}}
                    <div x-show="open" class="border-t border-gray-100">
                        <div class="px-5 py-3">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-gray-500 border-b border-gray-100">
                                        <th class="text-left pb-2 font-medium">Item</th>
                                        <th class="text-center pb-2 font-medium">Cant.</th>
                                        <th class="text-right pb-2 font-medium">Precio</th>
                                        <th class="text-right pb-2 font-medium">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($order['items'] as $item)
                                        <tr class="{{ $item['status'] === 'cancelled' ? 'line-through text-gray-400' : '' }}">
                                            <td class="py-2 pr-4">
                                                <span>{{ $item['dish'] }}</span>
                                                @if ($item['modifiers'])
                                                    <span class="text-xs text-gray-500 ml-1">({{ $item['modifiers'] }})</span>
                                                @endif
                                                @if ($item['notes'])
                                                    <span class="text-xs text-sienna-600 italic ml-1">"{{ $item['notes'] }}"</span>
                                                @endif
                                            </td>
                                            <td class="py-2 text-center">{{ $item['quantity'] }}</td>
                                            <td class="py-2 text-right">${{ number_format($item['price'], 2) }}</td>
                                            <td class="py-2 text-right font-medium">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="font-bold text-espresso-800">
                                        <td colspan="3" class="pt-3 text-right">Total</td>
                                        <td class="pt-3 text-right">${{ number_format($order['total'], 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>

                            @if ($order['notes'])
                                <div class="mt-3 text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2">
                                    <span class="font-medium">Notas del pedido:</span> {{ $order['notes'] }}
                                </div>
                            @endif
                            @if ($order['internal_note'])
                                <div class="mt-2 text-sm text-sienna-700 bg-sienna-50 rounded-lg px-3 py-2">
                                    <span class="font-medium">Nota interna:</span> {{ $order['internal_note'] }}
                                </div>
                            @endif
                        </div>

                        {{-- Close bill action --}}
                        @if (in_array($order['status'], ['ready', 'served']))
                            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                                <template x-if="closeOrderId !== {{ $order['id'] }}">
                                    <button
                                        @click="closeOrderId = {{ $order['id'] }}; paymentMethod = ''"
                                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors"
                                    >
                                        Cobrar
                                    </button>
                                </template>
                                <template x-if="closeOrderId === {{ $order['id'] }}">
                                    <div class="flex items-center gap-2">
                                        <select
                                            x-model="paymentMethod"
                                            class="text-sm border border-gray-300 rounded-lg px-3 py-2"
                                        >
                                            <option value="">Seleccionar método</option>
                                            <option value="cash">Efectivo</option>
                                            <option value="card">Tarjeta</option>
                                            <option value="transfer">Transferencia</option>
                                            <option value="other">Otro</option>
                                        </select>
                                        <button
                                            @click="if(paymentMethod) { $wire.closeOrder({{ $order['id'] }}, paymentMethod); closeOrderId = null; paymentMethod = ''; }"
                                            :disabled="!paymentMethod"
                                            class="px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-green-300 text-white text-sm font-semibold rounded-lg transition-colors"
                                        >
                                            Confirmar pago
                                        </button>
                                        <button
                                            @click="closeOrderId = null; paymentMethod = ''"
                                            class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors"
                                        >
                                            Cancelar
                                        </button>
                                    </div>
                                </template>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <span class="text-6xl block mb-4">🧾</span>
                    <h3 class="text-xl font-semibold text-gray-700">No hay cuentas activas</h3>
                    <p class="text-gray-500 mt-1">Las cuentas pendientes aparecerán aquí.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
