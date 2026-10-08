<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar restaurante</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">Registra tu restaurante</h1>

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/register" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del restaurante</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2" placeholder="Mi Restaurante">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subdominio</label>
                <input type="text" name="subdomain" value="{{ old('subdomain') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2" placeholder="mirestaurante">
                <p class="text-xs text-gray-500 mt-1">Será: mirestaurante.localhost</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tu nombre</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2" placeholder="Tu nombre">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2" placeholder="tu@email.com">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2" placeholder="Mínimo 8 caracteres">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2">
            </div>
            <button type="submit"
                class="w-full bg-amber-700 text-white font-semibold py-3 px-6 rounded-xl hover:bg-amber-800">
                Crear restaurante (prueba gratis 14 días)
            </button>
        </form>
    </div>
</body>
</html>
