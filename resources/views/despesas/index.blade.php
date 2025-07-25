<x-layout.app>
    <div class="min-h-screen bg-gray-100 px-4 py-6">
        <div class="mx-auto">
            <h1 class="text-3xl font-bold text-green-700 mb-4">Despesas do Deputado</h1>

            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-center">Deputado</th>
                            <th class="px-4 py-3 text-center">Tipo de Despesa</th>
                            <th class="px-4 py-3 text-center">Valor</th>
                            <th class="px-4 py-3 text-center">Ações</th>
                        </tr>
                        <tr class="bg-white border-b-gray-200">
                            <form>
                                <td class="px-4 py-2 text-center">
                                    <input type="text" name="deputado" value="{{ $filter['deputado'] ?? '' }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="text" name="tipo_despesa" value="{{ $filter['tipo_despesa'] ?? '' }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="text" name="valor_documento" value="{{ $filter['valor_documento'] ?? '' }}"
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-2 text-center align-middle">
                                    <button
                                        type="submit"
                                        class="px-3 py-1 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700 transition"
                                    >
                                        Filtrar
                                    </button>
                                    <a href="{{ route('despesas.index') }}"
                                        class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 transition ml-2">
                                        Limpar
                                    </a>
                                </td>
                            </form>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($despesas as $despesa)
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="px-4 py-3 text-center">{{ $despesa['deputado']->nome }}</td>
                                <td class="px-4 py-3 text-center">{{ $despesa['tipo_despesa'] }}</td>
                                <td class="px-4 py-3 text-center text-green-700 font-semibold">
                                    R$ {{ number_format($despesa['valor_documento'], 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center items-center gap-x-2">
                                        <a href="{{ route('despesa.show', ['id' => $despesa['id']]) }}">
                                            <button
                                                type="button"
                                                class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
                                            >
                                                Ver Dados
                                            </button>
                                        </a>
                                        @if (!empty($despesa['url_documento']))
                                            <a
                                                href="{{ $despesa['url_documento'] }}"
                                                target="_blank"
                                            >
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
                                                >
                                                    Ver PDF
                                                </button>
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('despesa.destroy', ['id' => $despesa['id']]) }}">
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="id" value="{{ $despesa['id'] }}">
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center mx-2 px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition"
                                            >
                                                Remover
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white">
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                                    Nenhuma despesa encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($despesas)
                        <tfoot>
                            <tr>
                                <td colspan="4" class="px-3 py-3">
                                    <div class="flex justify-center text-yellow-600">
                                        {{ $despesas->links() }}
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
