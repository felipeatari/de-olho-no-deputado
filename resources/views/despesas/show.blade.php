<x-layout.app>
    <div class="container mx-auto px-4 py-8 text-white">
        <div class="max-w-2xl mx-auto bg-gray-900 rounded-lg shadow p-6">
            <div class="flex justify-between font-semibold">
                <h1 class="text-2xl font-bold mb-4 text-yellow-400">Detalhes do Deputado</h1>
                <p class="text-sm text-gray-400">ID: {{ $deputado->id }}</p>
            </div>

            <div class="flex flex-col items-center space-y-4 mb-6 py-4">
                @if($deputado->url_foto)
                    <img src="{{ $deputado->url_foto }}" alt="Foto de {{ $deputado->nome }}" class="w-32 h-32 rounded-full shadow-lg">
                @endif
                <h2 class="text-xl font-semibold">{{ $deputado->nome }}</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm py-4">
                <div><span class="font-bold">Partido:</span> {{ $deputado->sigla_partido }}</div>
                <div><span class="font-bold">UF:</span> {{ $deputado->sigla_uf }}</div>
                <div><span class="font-bold">Legislatura:</span> {{ $deputado->id_legislatura }}</div>
                <div><span class="font-bold">Email:</span> {{ $deputado->email ?? 'Não informado' }}</div>
            </div>

            <div class="mt-6">
                <a href="{{ $backUrl }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    ← Voltar à lista
                </a>
            </div>
        </div>
    </div>
</x-layout.app>
