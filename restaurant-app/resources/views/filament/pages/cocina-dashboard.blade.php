@php
    $orders = $this->getOrdersData();
    $summary = $this->getSummary();
@endphp

<div
    x-data="{
        viewMode: '{{ $this->viewMode }}',
        autoRefresh: null,
        init() {
            this.autoRefresh = setInterval(() => {
                if (document.visibilityState === 'visible') {
                    $wire.$refresh();
                }
            }, 15000);
        },
        destroy() {
            clearInterval(this.autoRefresh);
        }
    }"
    class="space-y-6"
>
    {{-- Summary cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Pedidos activos</p>
            <p class="text-2xl font-bold text-espresso-800">{{ $summary['orders_pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Pendientes</p>
            <p class="text-2xl font-bold text-sienna-500">{{ $summary['pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-4">
            <p class="text-sm text-gray-500">Preparando</p>
            <p class="text-2xl font-bold text-amber-600">{{ $summary['preparing'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-green-200 p-4">
            <p class="text-sm text-gray-500">Listos</p>
            <p class="text-2xl font-bold text-green-600">{{ $summary['ready'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-200 p-4">
            <p class="text-sm text-gray-500">En demora</p>
            <p class="text-2xl font-bold text-red-600">{{ $summary['overdue'] }}</p>
        </div>
    </div>

    {{-- View toggle --}}
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">Comandas</h2>
        <div class="flex gap-2">
            <button
                @click="viewMode = 'grouped'"
                :class="viewMode === 'grouped' ? 'bg-espresso-800 text-white' : 'bg-white text-gray-700'"
                class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 transition-colors"
            >
                Por mesa
            </button>
            <button
                @click="viewMode = 'sequential'"
                :class="viewMode === 'sequential' ? 'bg-espresso-800 text-white' : 'bg-white text-gray-700'"
                class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 transition-colors"
            >
                Secuencial
            </button>
            <button
                @click="viewMode = 'stations'"
                :class="viewMode === 'stations' ? 'bg-espresso-800 text-white' : 'bg-white text-gray-700'"
                class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 transition-colors"
            >
                Por estación
            </button>
            <button
                @click="viewMode = 'batch'"
                :class="viewMode === 'batch' ? 'bg-espresso-800 text-white' : 'bg-white text-gray-700'"
                class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 transition-colors"
            >
                Por lote
            </button>
        </div>
    </div>

    {{-- Orders list --}}
    <div x-show="viewMode === 'grouped'" class="space-y-4">
        @forelse ($orders as $order)
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                x-data="{ open: true }"
            >
                {{-- Order header --}}
                <div
                    @click="open = !open"
                    class="flex items-center justify-between px-5 py-3 cursor-pointer border-b border-gray-100 {{ $order['priority'] === 'vip' ? 'bg-purple-50' : ($order['priority'] === 'urgent' ? 'bg-red-50' : '') }}"
                >
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-bold text-espresso-900">Mesa {{ $order['table_number'] }}</span>
                        <span class="text-sm text-gray-500">{{ $order['zone'] }}</span>
                        <span class="text-sm text-gray-500">· {{ $order['waiter'] }}</span>
                        <x-cocina-priority-badge :priority="$order['priority']" />
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-400">{{ $order['created_at'] }}</span>
                        <span class="text-xs {{ $order['minutes_ago'] > 15 ? 'text-red-600 font-bold' : ($order['minutes_ago'] > 10 ? 'text-amber-600' : 'text-gray-400') }}">
                            {{ $order['created_at_diff'] }}
                        </span>
                        <span class="text-sm font-medium">
                            @if ($order['pending_count'] > 0)
                                <span class="text-sienna-500">{{ $order['pending_count'] }} pend.</span>
                            @endif
                            @if ($order['preparing_count'] > 0)
                                <span class="text-amber-600">{{ $order['preparing_count'] }} prep.</span>
                            @endif
                            @if ($order['ready_count'] > 0)
                                <span class="text-green-600">{{ $order['ready_count'] }} listo</span>
                            @endif
                        </span>
                        <svg class="w-5 h-5 text-gray-400" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Order items --}}
                <div x-show="open" class="divide-y divide-gray-100">
                    @foreach ($order['items'] as $item)
                        <div
                            x-data="{
                                kitchenNote: '{{ $item['kitchen_note'] ?? '' }}',
                                showReturnForm: false,
                                returnReason: '',
                                saving: false,
                                saveNote() {
                                    this.saving = true;
                                    $wire.saveKitchenNote({{ $item['id'] }}, this.kitchenNote || null)
                                        .then(() => { this.saving = false; });
                                },
                                submitReturn() {
                                    $wire.returnItemWithReason({{ $item['id'] }}, this.returnReason);
                                    this.showReturnForm = false;
                                    this.returnReason = '';
                                }
                            }"
                            class="px-5 py-3 {{ $item['status'] === 'ready' ? 'bg-green-50' : ($item['status'] === 'cancelled' ? 'bg-gray-50 opacity-60' : ($item['return_reason'] ? 'bg-orange-50 border-l-4 border-orange-400' : '')) }}"
                        >
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    @if ($item['photo'])
                                        <img src="{{ $item['photo'] }}" alt="" class="w-8 h-8 rounded-lg object-cover shrink-0 border border-gray-200" />
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $item['quantity'] }}x {{ $item['dish'] }}</span>
                                    @if ($item['modifiers'])
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $item['modifiers'] }}</span>
                                    @endif
                                    @if ($item['notes'])
                                        <span class="text-xs text-sienna-600 italic">"{{ $item['notes'] }}"</span>
                                    @endif
                                    @if ($item['kitchen_note'])
                                        <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">👨‍🍳 {{ $item['kitchen_note'] }}</span>
                                    @endif
                                </div>

                                {{-- Return reason badge --}}
                                @if ($item['return_reason'])
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-orange-600 font-medium">🔄 Devuelto: {{ $item['return_reason'] }}</span>
                                        @if ($item['returned_at'])
                                            <span class="text-xs text-gray-400">{{ $item['returned_at'] }}</span>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex items-center gap-2 mt-1">
                                    @php
                                        $ageMinutes = $item['minutes_since_created'];
                                        $prepMinutes = $item['minutes_preparing'];
                                    @endphp
                                                <span class="text-xs {{ $item['status'] === 'pending' && $ageMinutes > 15 ? 'text-red-600 font-bold animate-pulse' : ($item['status'] === 'pending' && $ageMinutes > 10 ? 'text-amber-600' : 'text-gray-400') }}">
                                        @if ($item['status'] === 'pending')
                                            Pendiente {{ $ageMinutes }} min
                                        @elseif ($item['status'] === 'preparing')
                                            En prep. {{ $prepMinutes }} min
                                        @elseif ($item['status'] === 'ready')
                                            Listo ✅
                                        @elseif ($item['status'] === 'cancelled')
                                            Cancelado
                                        @endif
                                    </span>
                                    @if ($item['status'] === 'pending')
                                        <span class="w-2 h-2 rounded-full {{ $ageMinutes > 15 ? 'bg-red-500' : ($ageMinutes > 10 ? 'bg-amber-500' : 'bg-gray-300') }}"></span>
                                    @elseif ($item['status'] === 'preparing')
                                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                                    @endif
                                </div>

                                {{-- Kitchen note input --}}
                                @if ($item['status'] === 'preparing')
                                    <div class="flex items-center gap-2 mt-2">
                                        <input
                                            x-model="kitchenNote"
                                            type="text"
                                            placeholder="Nota de cocina..."
                                            class="flex-1 text-xs border border-gray-300 rounded-lg px-2 py-1 focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                                        />
                                        <button
                                            @click="saveNote()"
                                            x-text="saving ? '...' : 'Guardar'"
                                            :disabled="saving"
                                            class="text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors disabled:opacity-50"
                                        ></button>
                                    </div>
                                @endif

                                {{-- Return form --}}
                                @if ($item['status'] === 'ready' && $showReturnForm)
                                    <div class="flex items-center gap-2 mt-2">
                                        <input
                                            x-model="returnReason"
                                            type="text"
                                            placeholder="Motivo de devolución..."
                                            class="flex-1 text-xs border border-orange-300 rounded-lg px-2 py-1 focus:border-orange-400 focus:ring-1 focus:ring-orange-400"
                                        />
                                        <button
                                            @click="submitReturn()"
                                            class="text-xs px-2 py-1 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors"
                                        >
                                            Devolver
                                        </button>
                                        <button
                                            @click="showReturnForm = false; returnReason = ''"
                                            class="text-xs px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors"
                                        >
                                            Cancelar
                                        </button>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 shrink-0" x-data="{ showWaste: false }">
                                @if ($item['status'] === 'pending')
                                    <button
                                        wire:click="startPreparing({{ $item['id'] }})"
                                        class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors"
                                    >
                                        Preparar
                                    </button>
                                    <button
                                        wire:click="cancelItem({{ $item['id'] }})"
                                        class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                                        title="Cancelar"
                                    >
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <div class="relative">
                                        <button @click="showWaste = !showWaste" class="p-2 text-gray-400 hover:text-sienna-600 transition-colors" title="Registrar merma">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                        <div x-show="showWaste" @click.outside="showWaste = false" class="absolute right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 w-48 py-1">
                                            <button @click="showWaste = false; $wire.registerWaste({{ $item['id'] }}, 'overcooked')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Sobre cocción</button>
                                            <button @click="showWaste = false; $wire.registerWaste({{ $item['id'] }}, 'mistake')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Error preparación</button>
                                            <button @click="showWaste = false; $wire.registerWaste({{ $item['id'] }}, 'spoiled')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Ingrediente mal estado</button>
                                            <button @click="showWaste = false; $wire.registerWaste({{ $item['id'] }}, 'overproduction')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Sobreproducción</button>
                                            <button @click="showWaste = false; $wire.registerWaste({{ $item['id'] }}, 'other')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Otro</button>
                                        </div>
                                    </div>
                                @elseif ($item['status'] === 'preparing')
                                    <button
                                        wire:click="markReady({{ $item['id'] }})"
                                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors"
                                    >
                                        Listo
                                    </button>
                                    <button
                                        wire:click="cancelItem({{ $item['id'] }})"
                                        class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                                        title="Cancelar"
                                    >
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <div class="relative">
                                        <button @click="showWaste = !showWaste" class="p-2 text-gray-400 hover:text-sienna-600 transition-colors" title="Registrar merma">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                        <div x-show="showWaste" @click.outside="showWaste = false" class="absolute right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 w-48 py-1">
                                            <button @click="showWaste = false; $wire.registerWaste({{ $item['id'] }}, 'overcooked')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Sobre cocción</button>
                                            <button @click="showWaste = false; $wire.registerWaste({{ $item['id'] }}, 'mistake')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Error preparación</button>
                                            <button @click="showWaste = false; $wire.registerWaste({{ $item['id'] }}, 'spoiled')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Ingrediente mal estado</button>
                                            <button @click="showWaste = false; $wire.registerWaste({{ $item['id'] }}, 'overproduction')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Sobreproducción</button>
                                            <button @click="showWaste = false; $wire.registerWaste({{ $item['id'] }}, 'other')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">Otro</button>
                                        </div>
                                    </div>
                                @elseif ($item['status'] === 'ready')
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-green-600 font-medium">✔ Listo</span>
                                        <button
                                            @click="showReturnForm = !showReturnForm"
                                            class="text-sm text-orange-600 hover:text-orange-800 font-medium transition-colors"
                                            title="Devolver a cocina"
                                        >
                                            Devolver
                                        </button>
                                    </div>
                                @elseif ($item['status'] === 'cancelled')
                                    <span class="text-sm text-gray-400">Cancelado</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order footer --}}
                <div x-show="open" class="flex items-center justify-between px-5 py-2 bg-gray-50 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">Prioridad:</span>
                        <select
                            wire:change="setPriority({{ $order['id'] }}, $event.target.value)"
                            class="text-xs border border-gray-300 rounded-lg px-2 py-1"
                        >
                            <option value="normal" @selected($order['priority'] === 'normal')>Normal</option>
                            <option value="urgent" @selected($order['priority'] === 'urgent')>Urgente</option>
                            <option value="vip" @selected($order['priority'] === 'vip')>VIP</option>
                        </select>
                    </div>
                    <span class="text-xs text-gray-400">{{ $order['total_items'] }} items · {{ $order['cancelled_count'] > 0 ? $order['cancelled_count'] . ' cancelados' : '' }}</span>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <span class="text-6xl block mb-4">🍽️</span>
                <h3 class="text-xl font-semibold text-gray-700">No hay comandas activas</h3>
                <p class="text-gray-500 mt-1">Los pedidos aparecerán aquí automáticamente.</p>
            </div>
        @endforelse
    </div>

    {{-- Sequential view --}}
    <div x-show="viewMode === 'sequential'" class="space-y-3">
        @php
            $allItems = collect($orders)->flatMap(fn ($o) => collect($o['items'])->map(fn ($i) => array_merge($i, [
                'table_number' => $o['table_number'],
                'order_id' => $o['id'],
                'zone' => $o['zone'],
                'priority' => $o['priority'],
                'created_at' => $o['created_at'],
                'minutes_ago' => $o['minutes_ago'],
            ])))->sortByDesc(fn ($i) => match($i['priority']) {
                'vip' => 0, 'urgent' => 1, 'normal' => 2
            })->sortBy('minutes_since_created');
        @endphp

        @forelse ($allItems as $item)
            <div
                x-data="{ showReturn: false, returnReason: '' }"
                class="flex items-center justify-between bg-white rounded-lg shadow-sm border px-4 py-3 {{ $item['status'] === 'pending' && $item['minutes_since_created'] > 15 ? 'border-red-300 bg-red-50' : ($item['status'] === 'pending' && $item['minutes_since_created'] > 10 ? 'border-amber-300 bg-amber-50' : ($item['return_reason'] ? 'border-orange-300 bg-orange-50' : 'border-gray-200')) }}"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <span class="font-bold text-espresso-900 shrink-0">M{{ $item['table_number'] }}</span>
                    @if ($item['photo'])
                        <img src="{{ $item['photo'] }}" alt="" class="w-7 h-7 rounded object-cover shrink-0 border border-gray-200" />
                    @endif
                    <span class="font-medium text-gray-900 truncate">{{ $item['quantity'] }}x {{ $item['dish'] }}</span>
                    @if ($item['modifiers'])
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full shrink-0">{{ $item['modifiers'] }}</span>
                    @endif
                    @if ($item['notes'])
                        <span class="text-xs text-sienna-600 italic truncate">"{{ $item['notes'] }}"</span>
                    @endif
                    @if ($item['kitchen_note'])
                        <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full shrink-0">👨‍🍳 {{ $item['kitchen_note'] }}</span>
                    @endif
                    @if ($item['return_reason'])
                        <span class="text-xs text-orange-600 font-medium shrink-0">🔄 {{ $item['return_reason'] }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span class="text-xs {{ $item['status'] === 'pending' && $item['minutes_since_created'] > 15 ? 'text-red-600 font-bold' : ($item['status'] === 'pending' && $item['minutes_since_created'] > 10 ? 'text-amber-600' : 'text-gray-400') }}">
                        {{ $item['minutes_since_created'] }} min
                    </span>
                    @if ($item['status'] === 'pending')
                        <button wire:click="startPreparing({{ $item['id'] }})" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded-lg transition-colors">Preparar</button>
                    @elseif ($item['status'] === 'preparing')
                        <span class="text-xs text-amber-600">{{ $item['minutes_preparing'] ?? 0 }} min</span>
                        <button wire:click="markReady({{ $item['id'] }})" class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-lg transition-colors">Listo</button>
                    @elseif ($item['status'] === 'ready')
                        <span class="text-xs text-green-600 font-medium">✔ Listo</span>
                        <template x-if="!showReturn">
                            <button @click="showReturn = true" class="text-xs text-orange-600 hover:text-orange-800 font-medium">Devolver</button>
                        </template>
                        <template x-if="showReturn">
                            <div class="flex items-center gap-1">
                                <input x-model="returnReason" type="text" placeholder="Motivo..." class="w-24 text-xs border border-orange-300 rounded px-1.5 py-1" />
                                <button @click="$wire.returnItemWithReason({{ $item['id'] }}, returnReason); showReturn = false; returnReason = ''" class="text-xs px-2 py-1 bg-orange-500 text-white rounded">Ok</button>
                                <button @click="showReturn = false; returnReason = ''" class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">✕</button>
                            </div>
                        </template>
                    @endif
                    @if (in_array($item['status'], ['pending', 'preparing']))
                        <button wire:click="cancelItem({{ $item['id'] }})" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors" title="Cancelar">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <span class="text-6xl block mb-4">🍽️</span>
                <h3 class="text-xl font-semibold text-gray-700">No hay items pendientes</h3>
            </div>
        @endforelse
    </div>

    {{-- Station view --}}
    @php
        $stations = $this->getStationData();
    @endphp
    <div x-show="viewMode === 'stations'" x-cloak class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($stations as $station)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 bg-espresso-800 text-cream-50 font-semibold flex items-center justify-between">
                    <span>{{ $station['station'] }}</span>
                    <span class="text-xs bg-cream-200 text-espresso-800 px-2 py-0.5 rounded-full">{{ $station['count'] }}</span>
                </div>
                <div class="divide-y divide-gray-100 max-h-[70vh] overflow-y-auto">
                    @foreach ($station['items'] as $item)
                        <div class="px-4 py-3 {{ $item['status'] === 'preparing' ? 'bg-amber-50' : '' }} {{ $item['return_reason'] ? 'bg-orange-50' : '' }}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        @if ($item['photo'])
                                            <img src="{{ $item['photo'] }}" alt="" class="w-7 h-7 rounded object-cover shrink-0 border border-gray-200" />
                                        @endif
                                        <span class="font-medium text-sm text-gray-900">{{ $item['quantity'] }}x {{ $item['dish'] }}</span>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">M{{ $item['table_number'] }}</span>
                                    </div>
                                    @if ($item['modifiers'])
                                        <span class="text-xs text-gray-600 italic block mt-0.5">{{ $item['modifiers'] }}</span>
                                    @endif
                                    @if ($item['notes'])
                                        <span class="text-xs text-sienna-600 italic block">"{{ $item['notes'] }}"</span>
                                    @endif
                                    @if ($item['kitchen_note'])
                                        <span class="text-xs bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded mt-1 inline-block">👨‍🍳 {{ $item['kitchen_note'] }}</span>
                                    @endif
                                    @if ($item['return_reason'])
                                        <span class="text-xs text-orange-600 font-medium block mt-0.5">🔄 {{ $item['return_reason'] }}</span>
                                    @endif
                                    <span class="text-xs {{ $item['status'] === 'pending' && $item['minutes_since_created'] > 15 ? 'text-red-600 font-bold' : ($item['status'] === 'pending' && $item['minutes_since_created'] > 10 ? 'text-amber-600' : 'text-gray-400') }} block mt-1">
                                        @if ($item['status'] === 'pending')
                                            {{ $item['minutes_since_created'] }} min
                                        @elseif ($item['status'] === 'preparing')
                                            Prep. {{ $item['minutes_preparing'] }} min
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    @if ($item['status'] === 'pending')
                                        <button wire:click="startPreparing({{ $item['id'] }})" class="px-2.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded-lg transition-colors">Prep.</button>
                                    @elseif ($item['status'] === 'preparing')
                                        <button wire:click="markReady({{ $item['id'] }})" class="px-2.5 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-lg transition-colors">Listo</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <span class="text-6xl block mb-4">🍽️</span>
                <h3 class="text-xl font-semibold text-gray-700">No hay items pendientes</h3>
            </div>
        @endforelse
    </div>

    {{-- Batch view --}}
    @php
        $batches = $this->getBatchData();
    @endphp
    <div x-show="viewMode === 'batch'" x-cloak class="space-y-4">
        @forelse ($batches as $batch)
            <div x-data="{ ready: false }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3 bg-espresso-800 text-cream-50">
                    <div class="flex items-center gap-3">
                        @if ($batch['items'][0]['photo'] ?? null)
                            <img src="{{ $batch['items'][0]['photo'] }}" alt="" class="w-8 h-8 rounded-lg object-cover border border-cream-300" />
                        @endif
                        <span class="font-bold text-lg">{{ $batch['dish'] }}</span>
                        <span class="text-sm bg-cream-200 text-espresso-800 px-3 py-0.5 rounded-full font-bold">{{ $batch['total_quantity'] }} uds</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-cream-300">Mesas: {{ implode(', ', $batch['tables']) }}</span>
                        <button
                            @click="ready = true; $wire.markBatchReady({{ json_encode($batch['item_ids']) }})"
                            class="px-4 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg transition-colors"
                        >
                            Listos todos
                        </button>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach ($batch['items'] as $item)
                        <div class="px-5 py-3 flex items-center justify-between {{ $item['status'] === 'preparing' ? 'bg-amber-50' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="font-medium text-gray-900">{{ $item['quantity'] }}x</span>
                                <span class="text-sm text-gray-600">M{{ $item['table_number'] }}</span>
                                @if ($item['modifiers'])
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $item['modifiers'] }}</span>
                                @endif
                                @if ($item['notes'])
                                    <span class="text-xs text-sienna-600 italic">"{{ $item['notes'] }}"</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs {{ $item['minutes_since_created'] > 15 ? 'text-red-600 font-bold' : ($item['minutes_since_created'] > 10 ? 'text-amber-600' : 'text-gray-400') }}">
                                    {{ $item['minutes_since_created'] }} min
                                </span>
                                @if ($item['status'] === 'pending')
                                    <button wire:click="startPreparing({{ $item['id'] }})" class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded-lg">Prep.</button>
                                @elseif ($item['status'] === 'preparing')
                                    <button wire:click="markReady({{ $item['id'] }})" class="px-2.5 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded-lg">Listo</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <span class="text-6xl block mb-4">🍽️</span>
                <h3 class="text-xl font-semibold text-gray-700">No hay lotes para preparar</h3>
            </div>
        @endforelse
    </div>
</div>
