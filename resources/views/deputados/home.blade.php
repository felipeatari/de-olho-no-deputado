<x-layout.app>
    <div class="min-h-screen bg-gray-100 px-4 py-6">
        <div class="mx-auto">
            <h1 class="text-3xl font-bold text-green-700 mb-4">Deputados (Dados Abertos)</h1>

            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-200 text-gray-700">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-center align-middle">Foto</th>
                            <th scope="col" class="px-4 py-3 text-center align-middle">Nome</th>
                            <th scope="col" class="px-4 py-3 text-center align-middle">Partido</th>
                            <th scope="col" class="px-4 py-3 text-center align-middle">UF</th>
                            <th scope="col" class="px-4 py-3 text-center align-middle">Ações</th>
                        </tr>
                        <tr class="bg-white border-b-gray-200">
                            <form>
                                <input type="hidden" name="pagina" value="{{ request()->get('pagina', 1) }}">
                                <input type="hidden" name="itens" value="{{ request()->get('itens', 5) }}">
                                <input type="hidden" name="ordem" value="{{ request()->get('ordem', 'ASC') }}">
                                <input type="hidden" name="ordenarPor" value="{{ request()->get('ordenarPor', 'nome') }}">

                                <td class="px-4 py-2 text-center align-middle">-</td>
                                <td class="px-4 py-2 text-center align-middle">
                                    <input type="text" name="nome" value="{{ $filter['nome'] ?? '' }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-2 text-center align-middle">
                                    <input type="text" name="siglaPartido" value="{{ $filter['siglaPartido'] ?? '' }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-2 text-center align-middle">
                                    <input type="text" name="siglaUf" value="{{ $filter['siglaUf'] ?? '' }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-2 text-center align-middle">
                                    <button
                                        type="submit"
                                        class="px-3 py-1 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700 transition"
                                    >
                                        Filtrar
                                    </button>
                                    <a
                                        href="{{ route('deputados.home') }}"
                                        class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 transition"
                                    >
                                        <button type="button">
                                            Limpar
                                        </button>
                                    </a>
                                </td>
                            </form>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deputados as $deputado)
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <img src="{{ $deputado['urlFoto'] }}" alt="{{ $deputado['nome'] }}"
                                        class="w-12 h-12 rounded-full object-cover mx-auto">
                                </td>
                                <td class="px-4 py-3 text-center align-middle font-medium text-gray-900">{{ $deputado['nome'] }}</td>
                                <td class="px-4 py-3 text-center align-middle">{{ $deputado['siglaPartido'] }}</td>
                                <td class="px-4 py-3 text-center align-middle">{{ $deputado['siglaUf'] }}</td>
                                <td class="px-4 py-3 text-center align-middle">
                                    <form method="POST" action="{{ route('deputados.sync') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $deputado['id'] }}">
                                        <input type="hidden" name="uri" value="{{ $deputado['uri'] }}">
                                        <input type="hidden" name="nome" value="{{ $deputado['nome'] }}">
                                        <input type="hidden" name="sigla_partido" value="{{ $deputado['siglaPartido'] }}">
                                        <input type="hidden" name="uri_partido" value="{{ $deputado['uriPartido'] }}">
                                        <input type="hidden" name="sigla_uf" value="{{ $deputado['siglaUf'] }}">
                                        <input type="hidden" name="id_legislatura" value="{{ $deputado['idLegislatura'] }}">
                                        <input type="hidden" name="url_foto" value="{{ $deputado['urlFoto'] }}">
                                        <input type="hidden" name="email" value="{{ $deputado['email'] }}">
                                        <button type="submit"
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition">
                                            Sincronizar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white">
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                    Nenhum deputado encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center bg-gray-100">
                                <div class="flex justify-center gap-2 flex-wrap text-sm">
                                    @if ($deputados)
                                        @php
                                            $order = ['first', 'self', 'next', 'last'];
                                        @endphp

                                        @foreach ($order as $rel)
                                            @if ($links->has($rel))
                                                @php
                                                    $link = $links[$rel];
                                                    $label = match($rel) {
                                                        'first' => 'Primeira',
                                                        'prev' => 'Anterior',
                                                        'next' => 'Próxima',
                                                        'last' => 'Última',
                                                        'self' => 'Atual',
                                                        default => ucfirst($rel),
                                                    };
                                                @endphp

                                                @if ($rel !== 'self')
                                                    <a
                                                        href="{{ route('deputados.home', explode('&', parse_url($link['href'], PHP_URL_QUERY))) }}"
                                                        class="inline-block px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded hover:bg-gray-200 transition"
                                                    >
                                                        {{ $label }}
                                                    </a>
                                                @else
                                                    @php
                                                        // Extrai o número da página da query string
                                                        parse_str(parse_url($link['href'], PHP_URL_QUERY), $queryParams);
                                                        $paginaAtual = $queryParams['pagina'] ?? '—';
                                                    @endphp

                                                    <span class="inline-block px-3 py-1 bg-blue-600 text-white rounded">
                                                        Página {{ $paginaAtual }}
                                                    </span>
                                                @endif
                                            @endif
                                        @endforeach

                                        <form method="GET" action="{{ route('deputados.home') }}" class="inline-flex items-center gap-2">
                                            @php
                                                // Extrai a query da página atual para manter os parâmetros
                                                $queryBase = [];
                                                if ($links->has('self')) {
                                                    parse_str(parse_url($links['self']['href'], PHP_URL_QUERY), $queryBase);
                                                }
                                            @endphp

                                            @foreach ($queryBase as $key => $value)
                                                @if ($key !== 'pagina')
                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                @endif
                                            @endforeach

                                            <input type="number" name="pagina"
                                                class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                                                placeholder="Página" min="1" required>

                                            <button type="submit"
                                                class="px-3 py-1 bg-gray-800 text-white rounded hover:bg-gray-700 transition text-sm">
                                                Ir
                                            </button>
                                        </form>
                                    @else
                                        <a
                                            href="{{ route('deputados.home') }}"
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition"
                                        >
                                            Voltar
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-layout.app>
