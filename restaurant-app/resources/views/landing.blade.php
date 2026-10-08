<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant SaaS — Gestión integral para tu restaurante</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="text-xl font-bold text-amber-700">RestaurantApp</div>
            <nav class="flex items-center gap-4">
                <a href="/login" class="text-gray-600 hover:text-amber-700">Iniciar sesión</a>
                <a href="/register" class="bg-amber-700 text-white px-4 py-2 rounded-lg hover:bg-amber-800">Registrar mi restaurante</a>
            </nav>
        </div>
    </header>

    <section class="bg-gradient-to-br from-amber-800 to-amber-600 text-white py-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Gestiona tu restaurante en un solo lugar</h1>
            <p class="text-xl mb-8 opacity-90">Mesas, órdenes, cocina, mesoneros y reservas — todo conectado.</p>
            <a href="/register" class="inline-block bg-white text-amber-800 font-semibold px-8 py-3 rounded-lg hover:bg-amber-50">
                Empezar prueba gratis
            </a>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Todo lo que tu restaurante necesita</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="text-3xl mb-3">🍽️</div>
                    <h3 class="text-xl font-semibold mb-2">Gestión de mesas</h3>
                    <p class="text-gray-600">Controla mesas, zonas y asignación de mesoneros en tiempo real.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="text-3xl mb-3">👨‍🍳</div>
                    <h3 class="text-xl font-semibold mb-2">Pantalla de cocina</h3>
                    <p class="text-gray-600">Comandas que llegan directo a la cocina con TV display.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="text-3xl mb-3">📅</div>
                    <h3 class="text-xl font-semibold mb-2">Reservas online</h3>
                    <p class="text-gray-600">Tus clientes reservan mesa desde el menú público.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-6xl mx-auto px-4 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} RestaurantApp
        </div>
    </footer>
</body>
</html>
