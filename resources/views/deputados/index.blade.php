<x-layout.app>
    <div class="min-h-screen bg-gray-100 px-4 py-6">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-green-700 mb-4">Deputados (Sincronizados)</h1>

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
                                    <button type="submit"
                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                        Filtrar
                                    </button>
                                    <a
                                        href="{{ route('deputados.index') }}"
                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition"
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
                                    <img src="{{ $deputado['url_foto'] }}" alt="{{ $deputado['nome'] }}"
                                        class="w-12 h-12 rounded-full object-cover mx-auto">
                                </td>
                                <td class="px-4 py-3 text-center align-middle text-gray-900">{{ $deputado['nome'] }}</td>
                                <td class="px-4 py-3 text-center align-middle">{{ $deputado['sigla_partido'] }}</td>
                                <td class="px-4 py-3 text-center align-middle">{{ $deputado['sigla_uf'] }}</td>
                                <td class="px-4 py-3 text-center align-middle flex">
                                    <a href="{{ route('deputado.show', ['id' => $deputado['id']]) }}">
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition">
                                            Ver Dados
                                        </button>
                                    </a>
                                    <form method="POST" action="{{ route('deputado.destroy', ['id' => $deputado['id']]) }}">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" name="id" value="{{ $deputado['id'] }}">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center mx-2 px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition">
                                            Remover
                                        </button>
                                    </form>
                                    <button
                                        type="button"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition">
                                        Sync Despesas
                                    </button>
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
                    @if ($deputados)
                        <tfoot>
                            <tr>
                                <td class="px-3 py-3" colspan="2">
                                    <div class="flex justify-center text-yellow-600">
                                        {{ $deputados->links() }}
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</x-layout.app>
