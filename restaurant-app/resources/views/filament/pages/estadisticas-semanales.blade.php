@php
    $week = $this->getWeekDates();
    $ranking = $this->getRanking();
    $totals = $this->getTotals();
    $maxSales = collect($ranking)->max('total_sales') ?: 1;
@endphp

<div class="space-y-6">
    {{-- Selector de semana --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button wire:click="previousWeek" class="p-2 rounded-lg hover:bg-gray-100 transition">
                    <x-filament::icon icon="heroicon-o-chevron-left" class="w-5 h-5 text-gray-600" />
                </button>
                <span class="text-lg font-semibold text-espresso-800 min-w-[200px] text-center">{{ $week['label'] }}</span>
                <button wire:click="nextWeek" class="p-2 rounded-lg hover:bg-gray-100 transition">
                    <x-filament::icon icon="heroicon-o-chevron-right" class="w-5 h-5 text-gray-600" />
                </button>
            </div>
            <button wire:click="thisWeek" class="text-sm bg-espresso-100 text-espresso-700 px-3 py-1.5 rounded-lg hover:bg-espresso-200 transition">
                Esta semana
            </button>
        </div>
    </div>

    {{-- Totales --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-espresso-50 rounded-xl border border-espresso-200 p-4">
            <p class="text-sm text-espresso-600">Ventas totales</p>
            <p class="text-2xl font-bold text-espresso-800">${{ number_format($totals['total_sales'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Pedidos</p>
            <p class="text-2xl font-bold text-espresso-800">{{ $totals['total_orders'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Mesas atendidas</p>
            <p class="text-2xl font-bold text-espresso-800">{{ $totals['total_tables'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Mesoneros activos</p>
            <p class="text-2xl font-bold text-espresso-800">{{ $totals['waiter_count'] }}</p>
        </div>
        <div class="bg-gold-50 rounded-xl border border-gold-200 p-4">
            <p class="text-sm text-gold-700">Tiempo promedio atención</p>
            <p class="text-2xl font-bold text-espresso-800">{{ $totals['avg_attention'] ? round($totals['avg_attention']) . ' min' : '—' }}</p>
        </div>
    </div>

    {{-- Ranking --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Ranking de mesoneros</h3>
        </div>

        @if (count($ranking) > 0)
            <div class="divide-y divide-gray-100">
                @foreach ($ranking as $i => $r)
                    @php
                        $barWidth = $maxSales > 0 ? ($r['total_sales'] / $maxSales) * 100 : 0;
                        $medal = match ($i) {
                            0 => '🥇',
                            1 => '🥈',
                            2 => '🥉',
                            default => "#{$i}",
                        };
                    @endphp
                    <div class="px-4 py-3 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            <span class="text-lg font-semibold text-gray-400 w-8 text-center">{{ $medal }}</span>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-medium text-espresso-800">{{ $r['waiter_name'] }}</span>
                                    <span class="font-bold text-espresso-800">${{ number_format($r['total_sales'], 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-espresso-500 h-2 rounded-full transition-all" style="width: {{ $barWidth }}%"></div>
                                </div>
                                <div class="flex gap-4 mt-1 text-xs text-gray-500">
                                    <span>{{ $r['order_count'] }} pedidos</span>
                                    <span>{{ $r['tables_served'] }} mesas</span>
                                    <span>Prom. ${{ number_format($r['avg_per_order'], 2) }}/pedido</span>
                                    @if ($r['avg_minutes'])
                                        <span class="text-gold-700 font-medium">
                                            ⏱ {{ round($r['avg_minutes']) }} min
                                            @if ($r['min_minutes'] !== $r['max_minutes'])
                                                ({{ $r['min_minutes'] }}–{{ $r['max_minutes'] }})
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center text-gray-400">
                No hay datos para esta semana.
            </div>
        @endif
    </div>
</div>
