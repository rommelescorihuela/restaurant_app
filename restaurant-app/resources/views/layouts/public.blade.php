<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Restaurant')) — Sabor & Tradición</title>

    @fonts

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-espresso-900 bg-cream-50 antialiased">

    <nav x-data="{ scrolled: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 40)" class="fixed top-0 inset-x-0 z-50 transition-all duration-300" :class="scrolled ? 'backdrop-blur-md bg-espresso-900/95 shadow-lg shadow-espresso-900/50' : 'bg-espresso-900/80'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <a href="/" class="flex items-center gap-2 text-cream-50 group">
                    <span class="text-2xl transition-transform duration-500 group-hover:scale-110">🍽</span>
                    <span class="font-display text-xl lg:text-2xl font-bold tracking-wide group-hover:text-gold-400 transition-colors">{{ config('app.name', 'Restaurant') }}</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="/" class="text-cream-200 hover:text-gold-400 transition-colors text-sm font-medium tracking-wide uppercase relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-gold-500 after:transition-all after:duration-300">Inicio</a>
                    <a href="/menu" class="text-cream-200 hover:text-gold-400 transition-colors text-sm font-medium tracking-wide uppercase relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-gold-500 after:transition-all after:duration-300">Menú</a>
                    <a href="/#reservas" class="text-cream-200 hover:text-gold-400 transition-colors text-sm font-medium tracking-wide uppercase relative after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-0 hover:after:w-full after:bg-gold-500 after:transition-all after:duration-300">Reservas</a>
                    @auth
                        <a href="/dashboard" class="text-cream-200 hover:text-gold-400 transition-colors text-sm font-medium tracking-wide uppercase">Panel</a>
                    @else
                        <a href="{{ route('login') }}" class="text-cream-200 hover:text-gold-400 transition-colors text-sm font-medium tracking-wide uppercase">Acceder</a>
                    @endauth
                </div>

                <a href="/#reservas" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gold-500 hover:bg-gold-600 text-espresso-900 font-semibold text-sm rounded-full transition-all duration-300 hover:shadow-lg hover:shadow-gold-500/25 hover:-translate-y-0.5 active:translate-y-0">
                    Reservar Mesa
                </a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-espresso-950 border-t border-gold-500/10 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle at 50% 0%, #C8A45C 0%, transparent 50%);"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                <div>
                    <div class="flex items-center gap-2 text-cream-50 mb-4">
                        <span class="text-2xl">🍽</span>
                        <span class="font-display text-xl font-bold">{{ config('app.name', 'Restaurant') }}</span>
                    </div>
                    <p class="text-cream-400 text-sm leading-relaxed">
                        Donde cada platillo cuenta una historia. Cocina tradicional con un toque contemporáneo.
                    </p>
                </div>
                <div>
                    <h4 class="font-display text-cream-100 text-lg font-semibold mb-4">Horarios</h4>
                    <ul class="space-y-2 text-cream-400 text-sm">
                        <li class="flex justify-between"><span>Lunes - Viernes</span><span class="text-cream-200">12:00 - 23:00</span></li>
                        <li class="flex justify-between"><span>Sábado</span><span class="text-cream-200">10:00 - 00:00</span></li>
                        <li class="flex justify-between"><span>Domingo</span><span class="text-cream-200">10:00 - 22:00</span></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-display text-cream-100 text-lg font-semibold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-cream-400 text-sm">
                        <li class="flex items-center gap-2">📍 Calle Principal 123, Ciudad</li>
                        <li class="flex items-center gap-2">📞 +1 (555) 123-4567</li>
                        <li class="flex items-center gap-2">✉️ info@restaurante.com</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gold-500/10 text-center text-cream-500 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name', 'Restaurant') }}. Todos los derechos reservados.
            </div>
        </div>
    </footer>

</body>
</html>
