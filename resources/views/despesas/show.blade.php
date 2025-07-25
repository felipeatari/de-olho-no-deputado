<x-layout.app>
    <div class="container mx-auto px-4 py-8 text-white">
        <div class="max-w-2xl mx-auto bg-gray-900 rounded-lg shadow p-6">
            <div class="flex justify-between font-semibold">
                <h1 class="text-2xl font-bold mb-4 text-yellow-400">Detalhes da Despesa</h1>
                <p class="text-sm text-gray-400">ID: {{ $despesa->id }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm py-4">
                <div><span class="font-bold">Deputado:</span> {{ $despesa->deputado->nome ?? 'Desconhecido' }}</div>
                <div><span class="font-bold">Tipo de Despesa:</span> {{ $despesa->tipo_despesa }}</div>
                <div><span class="font-bold">Tipo de Documento:</span> {{ $despesa->tipo_documento }}</div>
                <div><span class="font-bold">Código Documento:</span> {{ $despesa->cod_documento }}</div>
                <div><span class="font-bold">Data Documento:</span> {{ \Carbon\Carbon::parse($despesa->data_documento)->format('d/m/Y') }}</div>
                <div><span class="font-bold">Valor:</span> R$ {{ number_format($despesa->valor_documento, 2, ',', '.') }}</div>
                <div><span class="font-bold">Ano:</span> {{ $despesa->ano }}</div>
                <div><span class="font-bold">Mês:</span> {{ $despesa->mes }}</div>
                @if($despesa->url_documento)
                    <div class="col-span-2">
                        <span class="font-bold">Documento:</span>
                        <a href="{{ $despesa->url_documento }}" target="_blank" class="text-blue-400 underline">Visualizar PDF</a>
                    </div>
                @endif
            </div>

            <div class="mt-6">
                <a href="{{ $backUrl }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    ← Voltar à lista
                </a>
            </div>
        </div>
    </div>
</x-layout.app>
