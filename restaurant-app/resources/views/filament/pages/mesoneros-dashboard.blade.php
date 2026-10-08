@php
    $tables = $this->getTableData();
    $waiters = $this->getWaitersOnShift();
    $alerts = $this->getActiveAlerts();
    $availableWaiters = $this->getAvailableWaiters();
@endphp

<div class="space-y-6">
    {{-- Resumen --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Mesas totales</p>
            <p class="text-2xl font-bold text-espresso-800">{{ count($tables) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Mesoneros activos</p>
            <p class="text-2xl font-bold text-espresso-800">{{ count($waiters) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Alertas activas</p>
            <p class="text-2xl font-bold text-red-600">{{ count($alerts) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Mesas sin atender</p>
            <p class="text-2xl font-bold text-sienna-500">{{ collect($tables)->where('priority', 'warning')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Pedidos listos por entregar</p>
            <p class="text-2xl font-bold text-gold-600">{{ collect($tables)->where('priority', 'urgent')->count() }}</p>
        </div>
    </div>

    {{-- Alertas activas --}}
    @if (count($alerts) > 0)
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <h3 class="text-lg font-semibold text-red-800 mb-3 flex items-center gap-2">
                <x-filament::icon icon="heroicon-o-exclamation-triangle" class="w-5 h-5" />
                Alertas de servicio
            </h3>
            <div class="space-y-2">
                @foreach ($alerts as $alert)
                    <div class="flex items-center justify-between bg-white rounded-lg px-4 py-2 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-espresso-800">Mesa {{ $alert['table_number'] }}</span>
                            <span class="text-xs text-gray-500">— {{ $alert['description'] }}</span>
                            <span class="text-xs text-gray-400">({{ $alert['created_at'] }})</span>
                        </div>
                        <button
                            wire:click="resolveHelp({{ $alert['table_id'] }})"
                            class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full hover:bg-green-200 transition"
                        >
                            Atender
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Mesoneros activos --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <h3 class="text-lg font-semibold text-espresso-800 mb-3">Mesoneros en turno</h3>
        <div class="flex flex-wrap gap-3">
            @forelse ($waiters as $waiter)
                <div class="flex items-center gap-2 bg-cream-50 border border-cream-200 rounded-lg px-3 py-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <span class="text-sm font-medium text-espresso-700">{{ $waiter['name'] }}</span>
                    <span class="text-xs text-gray-500">({{ $waiter['tables_count'] }} mesas)</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">No hay mesoneros en turno activo</p>
            @endforelse
        </div>
    </div>

    {{-- Mapa de mesas --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <h3 class="text-lg font-semibold text-espresso-800 mb-3">Estado de mesas</h3>
        @php
            $grouped = collect($tables)->groupBy('zone');
        @endphp

        <div class="space-y-6">
            @foreach ($grouped as $zone => $zoneTables)
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ $zone }}</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        @foreach ($zoneTables as $table)
                            @php
                                $colors = [
                                    'normal' => 'border-gray-200 bg-white',
                                    'warning' => 'border-sienna-300 bg-sienna-50',
                                    'urgent' => 'border-red-300 bg-red-50',
                                    'help' => 'border-purple-400 bg-purple-50',
                                ];
                                $badgeColors = [
                                    'normal' => 'bg-green-100 text-green-700',
                                    'warning' => 'bg-sienna-100 text-sienna-700',
                                    'urgent' => 'bg-red-100 text-red-700',
                                    'help' => 'bg-purple-100 text-purple-700',
                                ];
                                $badgeText = [
                                    'normal' => 'Normal',
                                    'warning' => 'Sin atender',
                                    'urgent' => 'Listo por entregar',
                                    'help' => 'Necesita ayuda',
                                ];
                            @endphp
                            <div
                                class="rounded-lg border-2 {{ $colors[$table['priority']] }} p-3 flex flex-col gap-1.5"
                                x-data="{ mode: 'default' }"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-espresso-800 text-lg">Mesa {{ $table['number'] }}</span>
                                    <span class="text-xs text-gray-400">Cap.{{ $table['capacity'] }}</span>
                                </div>
                                <span class="text-xs {{ $badgeColors[$table['priority']] }} px-2 py-0.5 rounded-full self-start">
                                    {{ $badgeText[$table['priority']] }}
                                </span>

                                {{-- Mesas fusionadas --}}
                                @if ($table['has_merged'])
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full self-start">
                                        + {{ implode(', ', $table['merged_tables']) }}
                                    </span>
                                @endif

                                <span class="text-xs text-gray-500">
                                    @if ($table['waiter'] !== 'Sin asignar')
                                        {{ $table['waiter'] }}
                                    @else
                                        <span class="text-sienna-600 font-medium">Sin asignar</span>
                                    @endif
                                </span>
                                @if ($table['last_order'] !== null)
                                    <span class="text-xs text-gray-400">{{ $table['last_order'] }} min</span>
                                @endif

                                {{-- Botones de acción --}}
                                <template x-if="mode === 'default'">
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <button
                                            wire:click="startReassign({{ $table['id'] }})"
                                            @click="mode = 'reassign'"
                                            class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 transition"
                                        >
                                            Reasignar
                                        </button>
                                        <button
                                            wire:click="startMerge({{ $table['id'] }})"
                                            @click="mode = 'merge'"
                                            class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded hover:bg-gray-200 transition"
                                        >
                                            Fusionar
                                        </button>
                                        @if ($table['has_merged'])
                                            <button
                                                wire:click="splitTable({{ $table['id'] }})"
                                                class="text-xs bg-sienna-100 text-sienna-700 px-2 py-1 rounded hover:bg-sienna-200 transition"
                                            >
                                                Separar
                                            </button>
                                        @endif
                                        @if ($table['help_requested'])
                                            <button
                                                wire:click="resolveHelp({{ $table['id'] }})"
                                                class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded hover:bg-purple-200 transition"
                                            >
                                                Atender
                                            </button>
                                        @else
                                            <button
                                                wire:click="requestHelp({{ $table['id'] }})"
                                                class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded hover:bg-gray-200 transition"
                                            >
                                                Ayuda
                                            </button>
                                        @endif
                                    </div>
                                </template>

                                {{-- Panel de reasignación --}}
                                <template x-if="mode === 'reassign'">
                                    <div class="mt-1 flex flex-col gap-1.5">
                                        <select wire:model="newWaiterId" class="text-xs border border-gray-200 rounded px-2 py-1 w-full">
                                            <option value="">Seleccionar mesonero...</option>
                                            @foreach ($availableWaiters as $aw)
                                                <option value="{{ $aw['id'] }}">{{ $aw['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <div class="flex gap-1">
                                            <button wire:click="transferTable" @click="mode = 'default'"
                                                class="text-xs bg-espresso-600 text-white px-2 py-1 rounded hover:bg-espresso-700 transition flex-1 text-center">Confirmar</button>
                                            <button @click="mode = 'default'; $wire.cancelReassign()"
                                                class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded hover:bg-gray-200 transition flex-1 text-center">Cancelar</button>
                                        </div>
                                    </div>
                                </template>

                                {{-- Panel de fusión --}}
                                <template x-if="mode === 'merge'">
                                    <div class="mt-1 flex flex-col gap-1.5">
                                        <select wire:model="mergeTargetId" class="text-xs border border-gray-200 rounded px-2 py-1 w-full">
                                            <option value="">Fusionar con...</option>
                                            @foreach ($this->getMergeCandidates($table['id']) as $mc)
                                                <option value="{{ $mc['id'] }}">Mesa {{ $mc['number'] }}</option>
                                            @endforeach
                                        </select>
                                        <div class="flex gap-1">
                                            <button wire:click="mergeTables" @click="mode = 'default'"
                                                class="text-xs bg-espresso-600 text-white px-2 py-1 rounded hover:bg-espresso-700 transition flex-1 text-center">Confirmar</button>
                                            <button @click="mode = 'default'; $wire.cancelMerge()"
                                                class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded hover:bg-gray-200 transition flex-1 text-center">Cancelar</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
