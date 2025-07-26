<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'De Olho No Deputado')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

    <header class="bg-green-700 text-white shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <img width="30" class="mr-2" src="{{ asset('images/logo.png') }}" alt="">
                <h1 class="font-semibold">De Olho No Deputado</h1>
            </div>
            <nav>
                <ul class="flex space-x-4 text-sm font-semibold">
                    <li><a href="{{ route('deputados.home') }}" class="hover:underline">Home</a></li>
                    <li><a href="{{ route('deputados.index') }}" class="hover:underline">Deputados</a></li>
                    <li><a href="{{ route('despesas.index') }}" class="hover:underline">Despesas</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        {{ $slot }}
    </main>

    <x-alert />

    <footer class="bg-gray-200 text-gray-600 py-4 mt-10">
        <div class="container mx-auto px-4 text-center text-sm">
            &copy; {{ date('Y') }} De Olho No Deputado. Dados fornecidos pela API da Câmara dos Deputados.
        </div>
    </footer>
</body>
</html>
