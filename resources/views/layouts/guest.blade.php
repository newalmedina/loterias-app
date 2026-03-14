<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login' }}</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    {{ $slot }}
    @livewireScripts
</body>
</html>
