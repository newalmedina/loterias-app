<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="max-w-md bg-white p-6 rounded shadow w-full">
    <h2 class="text-xl font-bold mb-4">Cambiar Contraseña</h2>

    @if (session('success'))
        <div class="bg-green-100 p-2 mb-4 rounded text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('change-password.update') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-medium">Nueva Contraseña</label>
            <input type="password" name="password" class="w-full border rounded p-2" required>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
            Cambiar Contraseña
        </button>
    </form>
</div>

</body>
</html>
