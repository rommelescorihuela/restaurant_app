@extends('layouts.public')

@section('title', 'Inicio')

@section('content')

    <section class="relative min-h-screen flex items-center bg-espresso-900 overflow-hidden noise-overlay">
        <div class="absolute inset-0 bg-gradient-to-br from-espresso-900 via-espresso-800 to-espresso-950">
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 25% 25%, #C8A45C 0%, transparent 50%), radial-gradient(circle at 75% 75%, #8B3A2A 0%, transparent 50%);"></div>
            <div class="absolute inset-0 opacity-10" style="background: linear-gradient(135deg, transparent 40%, rgba(200,164,92,0.08) 60%, transparent 80%); background-size: 400% 400%; animation: shimmer 8s ease-in-out infinite;"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20 lg:pt-40 lg:pb-32">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="space-y-8 entrance">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full entrance-d1">
                        <span class="w-2 h-2 bg-gold-500 rounded-full animate-pulse"></span>
                        <span class="text-gold-400 text-sm font-medium tracking-wide">NUEVO — Menú de Temporada</span>
                    </div>

                    <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-bold leading-tight entrance-d2">
                        <span class="text-cream-50">Donde el</span>
                        <br>
                        <span class="text-gold-400 italic bg-clip-text text-transparent bg-gradient-to-r from-gold-400 via-gold-300 to-gold-500">sabor</span>
                        <br>
                        <span class="text-cream-50">cuenta tu historia</span>
                    </h1>

                    <p class="text-lg lg:text-xl text-cream-300/90 max-w-lg leading-relaxed entrance-d3">
                        Descubre una experiencia culinaria única donde cada platillo es elaborado con ingredientes frescos y pasión por la cocina.
                    </p>

                    <div class="flex flex-wrap gap-4 entrance-d4">
                        <a href="/#reservas" class="group inline-flex items-center gap-2 px-8 py-4 bg-gold-500 hover:bg-gold-600 text-espresso-900 font-semibold text-base rounded-full transition-all duration-300 hover:shadow-xl hover:shadow-gold-500/30 hover:-translate-y-0.5 active:translate-y-0">
                            Reserva tu Mesa
                            <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="{{ route('menu', ['tenant' => tenant('id')]) }}" class="inline-flex items-center gap-2 px-8 py-4 border border-cream-500/30 text-cream-200 hover:bg-cream-50/5 font-medium text-base rounded-full transition-all duration-300 hover:border-cream-500/50 hover:text-cream-50">
                            Ver Menú
                        </a>
                    </div>

                    <div class="flex items-center gap-8 pt-4 entrance-d5">
                        <div class="text-center">
                            <span class="block font-display text-3xl font-bold text-gold-400 tabular-nums">15+</span>
                            <span class="text-cream-400 text-sm">Años de tradición</span>
                        </div>
                        <div class="w-px h-12 bg-cream-500/20"></div>
                        <div class="text-center">
                            <span class="block font-display text-3xl font-bold text-gold-400 tabular-nums">80+</span>
                            <span class="text-cream-400 text-sm">Platos exclusivos</span>
                        </div>
                        <div class="w-px h-12 bg-cream-500/20"></div>
                        <div class="text-center">
                            <span class="block font-display text-3xl font-bold text-gold-400 tabular-nums">4.9</span>
                            <span class="text-cream-400 text-sm">Valoración</span>
                        </div>
                    </div>
                </div>

                <div class="hidden lg:block relative entrance-d2">
                    <div class="relative aspect-square max-w-lg mx-auto">
                        <div class="absolute inset-0 bg-gradient-to-br from-gold-500/20 to-sienna-500/20 rounded-full blur-3xl animate-pulse"></div>
                        <div class="relative w-full h-full rounded-full border-2 border-gold-500/20 overflow-hidden bg-gradient-to-br from-espresso-700 to-espresso-900 flex items-center justify-center group">
                            <div class="text-center p-12 transition-transform duration-700 group-hover:scale-105">
                                <span class="block text-8xl mb-4 animate-float inline-block">🍽</span>
                                <span class="block font-display text-2xl text-cream-200">Arte culinario</span>
                                <div class="mt-6 flex justify-center gap-2">
                                    <span class="w-3 h-3 bg-gold-500 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                                    <span class="w-3 h-3 bg-gold-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                                    <span class="w-3 h-3 bg-gold-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-gold-500 rounded-full flex items-center justify-center animate-pulse shadow-lg shadow-gold-500/30">
                            <span class="text-espresso-900 font-bold text-sm text-center leading-tight">NUEVO<br>MENÚ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-32 bg-gradient-to-t from-cream-50 to-transparent"></div>
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-gold-500/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </section>

    <section class="py-20 lg:py-32 bg-cream-50 relative">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gold-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-sienna-500/5 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-2xl mx-auto mb-16 entrance">
                <span class="text-gold-600 font-semibold text-sm tracking-widest uppercase">Sabores que enamoran</span>
                <h2 class="font-display text-4xl lg:text-5xl font-bold text-espresso-900 mt-3">Nuestras Especialidades</h2>
                <div class="w-16 h-1 bg-gradient-to-r from-gold-500 to-gold-400 mx-auto mt-4 rounded-full"></div>
                <p class="text-espresso-600 mt-4 text-lg">Platos cuidadosamente seleccionados por nuestro chef para brindarte una experiencia inolvidable.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 entrance-d1">
                    <div class="aspect-[4/3] bg-gradient-to-br from-sienna-200 to-sienna-300 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-sienna-500/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="text-7xl group-hover:scale-110 transition-transform duration-300 relative z-10">🥗</span>
                    </div>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-gold-600 uppercase tracking-wider">Entradas</span>
                        <h3 class="font-display text-xl font-bold text-espresso-900 mt-1 group-hover:text-gold-700 transition-colors">Ensalada del Chef</h3>
                        <p class="text-espresso-500 text-sm mt-2">Mix de verdes con vinagreta balsámica, nueces y queso de cabra.</p>
                        <span class="inline-block mt-4 text-gold-700 font-bold text-lg tabular-nums">$12.90</span>
                    </div>
                </div>

                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 entrance-d2">
                    <div class="aspect-[4/3] bg-gradient-to-br from-sienna-300 to-sienna-400 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-sienna-500/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="text-7xl group-hover:scale-110 transition-transform duration-300 relative z-10">🍝</span>
                    </div>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-gold-600 uppercase tracking-wider">Platos Fuertes</span>
                        <h3 class="font-display text-xl font-bold text-espresso-900 mt-1 group-hover:text-gold-700 transition-colors">Pasta Artesanal</h3>
                        <p class="text-espresso-500 text-sm mt-2">Pasta fresca hecha a mano con salsa de la casa y hierbas mediterráneas.</p>
                        <span class="inline-block mt-4 text-gold-700 font-bold text-lg tabular-nums">$18.50</span>
                    </div>
                </div>

                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 entrance-d3">
                    <div class="aspect-[4/3] bg-gradient-to-br from-sienna-400 to-sienna-500 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-sienna-500/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <span class="text-7xl group-hover:scale-110 transition-transform duration-300 relative z-10">🍰</span>
                    </div>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-gold-600 uppercase tracking-wider">Postres</span>
                        <h3 class="font-display text-xl font-bold text-espresso-900 mt-1 group-hover:text-gold-700 transition-colors">Tiramisú Clásico</h3>
                        <p class="text-espresso-500 text-sm mt-2">Nuestra versión del clásico italiano con mascarpone y café.</p>
                        <span class="inline-block mt-4 text-gold-700 font-bold text-lg tabular-nums">$9.90</span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12 entrance-d2">
                <a href="{{ route('menu', ['tenant' => tenant('id')]) }}" class="group inline-flex items-center gap-2 px-8 py-4 bg-espresso-900 hover:bg-espresso-800 text-cream-50 font-semibold rounded-full transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                    Ver Menú Completo
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    <section id="reservas" class="py-20 lg:py-32 bg-espresso-900 relative overflow-hidden noise-overlay">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 30% 50%, #C8A45C 0%, transparent 50%), radial-gradient(circle at 70% 50%, #D06040 0%, transparent 50%);"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="entrance">
                    <span class="text-gold-400 font-semibold text-sm tracking-widest uppercase">Reserva tu experiencia</span>
                    <h2 class="font-display text-4xl lg:text-5xl font-bold text-cream-50 mt-3">¿Listo para una <span class="text-gold-400 italic">experiencia</span> única?</h2>
                    <p class="text-cream-300 mt-4 text-lg">Reserva tu mesa y déjate sorprender por nuestra cocina. Te esperamos.</p>
                    <div class="flex items-center gap-6 mt-8">
                        <div class="flex -space-x-3">
                            <div class="w-12 h-12 rounded-full bg-gold-500/20 border-2 border-gold-500/40 flex items-center justify-center text-gold-400 font-bold text-sm ring-2 ring-espresso-900">JM</div>
                            <div class="w-12 h-12 rounded-full bg-gold-500/20 border-2 border-gold-500/40 flex items-center justify-center text-gold-400 font-bold text-sm ring-2 ring-espresso-900">AL</div>
                            <div class="w-12 h-12 rounded-full bg-gold-500/20 border-2 border-gold-500/40 flex items-center justify-center text-gold-400 font-bold text-sm ring-2 ring-espresso-900">RC</div>
                            <div class="w-12 h-12 rounded-full bg-gold-500/20 border-2 border-gold-500/40 flex items-center justify-center text-gold-400 font-bold text-sm ring-2 ring-espresso-900">+2k</div>
                        </div>
                        <span class="text-cream-400 text-sm">Clientes satisfechos</span>
                    </div>
                </div>

                <div class="entrance-d1">
                    <div class="bg-cream-50 rounded-2xl p-8 shadow-xl shadow-espresso-950/30">
                        <h3 class="font-display text-2xl font-bold text-espresso-900 text-center">Haz tu Reserva</h3>
                        <p class="text-espresso-500 text-sm text-center mt-2">Completa el formulario y te confirmaremos tu reserva.</p>

                        @if (session('success'))
                            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm text-center">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('reservations.store', ['tenant' => tenant('id')]) }}" class="mt-6 space-y-4">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-espresso-700 mb-1">Nombre</label>
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Tu nombre" required class="w-full px-4 py-3 rounded-xl border border-espresso-200 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all @error('name') border-red-400 @enderror">
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-espresso-700 mb-1">Teléfono</label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000" required class="w-full px-4 py-3 rounded-xl border border-espresso-200 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all @error('phone') border-red-400 @enderror">
                                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-espresso-700 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required class="w-full px-4 py-3 rounded-xl border border-espresso-200 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all @error('email') border-red-400 @enderror">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-espresso-700 mb-1">Fecha</label>
                                    <input type="date" name="date" value="{{ old('date') }}" required class="w-full px-4 py-3 rounded-xl border border-espresso-200 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all @error('date') border-red-400 @enderror">
                                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-espresso-700 mb-1">Hora</label>
                                    <select name="time" required class="w-full px-4 py-3 rounded-xl border border-espresso-200 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all bg-white @error('time') border-red-400 @enderror">
                                        <option value="">Seleccionar</option>
                                        <option value="12:00" @selected(old('time') == '12:00')>12:00</option>
                                        <option value="13:00" @selected(old('time') == '13:00')>13:00</option>
                                        <option value="14:00" @selected(old('time') == '14:00')>14:00</option>
                                        <option value="19:00" @selected(old('time') == '19:00')>19:00</option>
                                        <option value="20:00" @selected(old('time') == '20:00')>20:00</option>
                                        <option value="21:00" @selected(old('time') == '21:00')>21:00</option>
                                    </select>
                                    @error('time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-espresso-700 mb-1">Comensales</label>
                                <select name="guests" required class="w-full px-4 py-3 rounded-xl border border-espresso-200 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition-all bg-white @error('guests') border-red-400 @enderror">
                                    <option value="">Seleccionar</option>
                                    @foreach ([1,2,3,4,5,6,7,8] as $n)
                                        <option value="{{ $n }}" @selected(old('guests') == $n)>{{ $n }} {{ $n == 1 ? 'persona' : 'personas' }}</option>
                                    @endforeach
                                </select>
                                @error('guests') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" class="w-full py-4 bg-gold-500 hover:bg-gold-600 text-espresso-900 font-bold rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-gold-500/25 active:scale-[0.98]">
                                Confirmar Reserva
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-32 bg-cream-50 relative">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-20 w-60 h-60 bg-gold-500/5 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-2xl mx-auto mb-16 entrance">
                <span class="text-gold-600 font-semibold text-sm tracking-widest uppercase">Testimonios</span>
                <h2 class="font-display text-4xl lg:text-5xl font-bold text-espresso-900 mt-3">Lo que dicen nuestros comensales</h2>
                <div class="w-16 h-1 bg-gradient-to-r from-gold-500 to-gold-400 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 entrance-d1">
                    <div class="flex gap-1 text-gold-500 mb-4">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="text-espresso-600 text-sm leading-relaxed">"Una experiencia gastronómica inolvidable. Cada plato es una obra de arte. El servicio es impecable."</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gold-200 to-gold-300 flex items-center justify-center font-bold text-espresso-700 text-sm">MC</div>
                        <div>
                            <span class="block text-sm font-semibold text-espresso-900">María C.</span>
                            <span class="text-xs text-espresso-400">Cliente frecuente</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 entrance-d2">
                    <div class="flex gap-1 text-gold-500 mb-4">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="text-espresso-600 text-sm leading-relaxed">"El ambiente es acogedor y la comida es espectacular. Recomiendo especialmente la pasta artesanal."</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gold-200 to-gold-300 flex items-center justify-center font-bold text-espresso-700 text-sm">AL</div>
                        <div>
                            <span class="block text-sm font-semibold text-espresso-900">Andrés L.</span>
                            <span class="text-xs text-espresso-400">Crítico gastronómico</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 entrance-d3">
                    <div class="flex gap-1 text-gold-500 mb-4">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="text-espresso-600 text-sm leading-relaxed">"Celebramos nuestro aniversario aquí y fue perfecto. La atención al detalle es extraordinaria."</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gold-200 to-gold-300 flex items-center justify-center font-bold text-espresso-700 text-sm">RP</div>
                        <div>
                            <span class="block text-sm font-semibold text-espresso-900">Raquel P.</span>
                            <span class="text-xs text-espresso-400">Cliente frecuente</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
