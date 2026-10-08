@extends('layouts.public')

@section('title', 'Menú')

@section('content')

    @if ($table)
        <div
            x-data="{
                sending: false,
                sent: false,
                error: false,
                send() {
                    this.sending = true;
                    this.error = false;
                    fetch('{{ route('menu.call-waiter', ['tenant' => tenant('id')]) }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ table_id: {{ $table->id }} })
                    })
                    .then(r => { if (!r.ok) throw Error(); return r.json(); })
                    .then(() => { this.sent = true; this.sending = false; })
                    .catch(() => { this.error = true; this.sending = false; });
                }
            }"
            class="fixed bottom-6 right-6 z-50"
        >
            <template x-if="!sent">
                <button
                    @click="send()"
                    :disabled="sending"
                    class="group flex items-center gap-2 px-5 py-3 bg-gold-500 hover:bg-gold-600 disabled:bg-gold-300 text-espresso-900 font-semibold rounded-full shadow-lg hover:shadow-xl hover:shadow-gold-500/30 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0"
                >
                    <svg class="w-5 h-5 shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span x-text="sending ? 'Enviando...' : 'Llamar Mesonero'"></span>
                </button>
            </template>
            <template x-if="sent">
                <div class="flex items-center gap-2 px-5 py-3 bg-emerald-600 text-white font-semibold rounded-full shadow-lg animate-bounce">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Mesonero en camino
                </div>
            </template>
            <template x-if="error">
                <div class="mt-2 px-4 py-2 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm text-center">
                    Error al enviar. Intenta de nuevo.
                </div>
            </template>
        </div>
    @endif

    <section class="pt-32 pb-20 bg-espresso-900 relative overflow-hidden noise-overlay">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 80% 20%, #C8A45C 0%, transparent 50%), radial-gradient(circle at 20% 80%, #8B3A2A 0%, transparent 50%);"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center entrance">
            <span class="text-gold-400 font-semibold text-sm tracking-widest uppercase">Descubre nuestros sabores</span>
            <h1 class="font-display text-5xl lg:text-6xl font-bold text-cream-50 mt-3">Nuestro Menú</h1>
            <div class="w-16 h-1 bg-gradient-to-r from-gold-500 to-gold-400 mx-auto mt-4 rounded-full"></div>
            <p class="text-cream-300 mt-4 text-lg max-w-2xl mx-auto">Cada platillo es preparado con ingredientes frescos y el cariño de nuestra cocina.</p>
        </div>
        <div class="absolute bottom-0 inset-x-0 h-24 bg-gradient-to-t from-cream-50 to-transparent"></div>
    </section>

    @if ($categories->isNotEmpty())
        <nav class="sticky top-16 lg:top-20 z-40 bg-cream-50/95 backdrop-blur-md border-b border-espresso-100 shadow-sm"
             x-data="{
                active: '{{ $categories->first()->slug ?? $categories->first()->id }}',
                scrollTo(id) {
                    this.active = id;
                    document.getElementById('cat-' + id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
             }">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 overflow-x-auto hide-scrollbar">
                <div class="flex gap-1 py-3 category-nav">
                    @foreach ($categories as $category)
                        @php $catId = $category->slug ?? $category->id; @endphp
                        <button
                            @click="scrollTo('{{ $catId }}')"
                            :class="active === '{{ $catId }}' ? 'active bg-gold-500/10 text-gold-700 font-semibold' : 'text-espresso-600 hover:text-espresso-900 hover:bg-espresso-100/50'"
                            class="whitespace-nowrap px-4 py-2 text-sm rounded-full transition-all duration-200"
                        >
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </nav>

        @foreach ($categories as $category)
            @php $catId = $category->slug ?? $category->id; @endphp
            <section id="cat-{{ $catId }}" class="py-16 lg:py-20 {{ $loop->even ? 'bg-cream-50' : 'bg-white' }}">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12 entrance">
                        <h2 class="font-display text-3xl lg:text-4xl font-bold text-espresso-900">{{ $category->name }}</h2>
                        @if ($category->description)
                            <p class="text-espresso-500 mt-2">{{ $category->description }}</p>
                        @endif
                        <div class="w-12 h-0.5 bg-gradient-to-r from-gold-500 to-gold-400 mx-auto mt-3 rounded-full"></div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        @forelse ($category->dishes as $dish)
                            <div class="dish-card group bg-white rounded-xl p-4 lg:p-5 shadow-sm border border-espresso-100 hover:border-gold-200/50 flex items-start gap-4 entrance-d2">
                                <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-xl bg-gradient-to-br from-espresso-100 to-espresso-200 shrink-0 overflow-hidden flex items-center justify-center">
                                    @if ($dish->getFirstMediaUrl('dish_photo'))
                                        <img src="{{ $dish->getFirstMediaUrl('dish_photo', 'thumb') }}" alt="{{ $dish->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-2xl">🍽</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-display text-base lg:text-lg font-bold text-espresso-900 group-hover:text-gold-700 transition-colors">
                                            {{ $dish->name }}
                                        </h3>
                                        <span class="font-display text-lg lg:text-xl font-bold text-gold-700 tabular-nums shrink-0">${{ number_format($dish->price, 2) }}</span>
                                    </div>
                                    @if ($dish->description)
                                        <p class="text-espresso-500 text-xs lg:text-sm mt-1 line-clamp-2">{{ $dish->description }}</p>
                                    @endif
                                    <span class="inline-flex items-center gap-1.5 mt-2 text-xs {{ $dish->is_available ? 'text-emerald-600' : 'text-red-500' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dish->is_available ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                        {{ $dish->is_available ? 'Disponible' : 'Agotado' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="col-span-full text-center text-espresso-400 py-8">Próximamente...</p>
                        @endforelse
                    </div>
                </div>
            </section>
        @endforeach
    @else
        <section class="py-20 bg-cream-50">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <span class="text-6xl block mb-4">🍽</span>
                <h2 class="font-display text-3xl font-bold text-espresso-900">Menú en preparación</h2>
                <p class="text-espresso-500 mt-2">Estamos preparando algo delicioso para ti. Vuelve pronto.</p>
            </div>
        </section>
    @endif

    <section class="py-20 bg-espresso-900 relative overflow-hidden noise-overlay">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 50% 50%, #C8A45C 0%, transparent 50%);"></div>
        <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center entrance">
            <h2 class="font-display text-3xl lg:text-4xl font-bold text-cream-50">¿Listo para disfrutar?</h2>
            <p class="text-cream-300 mt-3 text-lg">Reserva tu mesa y déjate consentir por nuestra cocina.</p>
            <a href="/#reservas" class="group inline-flex items-center gap-2 mt-6 px-8 py-4 bg-gold-500 hover:bg-gold-600 text-espresso-900 font-semibold rounded-full transition-all duration-300 hover:shadow-xl hover:shadow-gold-500/25 hover:-translate-y-0.5">
                Reservar Mesa
                <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </section>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

@endsection
