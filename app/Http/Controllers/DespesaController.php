<?php

namespace App\Http\Controllers;

use App\Services\DespesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DespesaController extends Controller
{
    public function __construct(
        protected DespesaService $despesaService,
    )
    {
    }

    public function getAllApi(Request $request)
    {
        $filter = [
            'pagina' => $request->get('pagina', 1),
            'itens' => $request->get('itens', 5),
            'ordem' => $request->get('ordem', 'ASC'),
            'ordenarPor' => $request->get('ordenarPor', 'nome'),
        ];

        if ($request->filled('nome')) {
            $filter['nome'] = $request->get('nome');
        }

        if ($request->filled('siglaPartido')) {
            $filter['siglaPartido'] = $request->get('siglaPartido');
        }

        if ($request->filled('siglaUf')) {
            $filter['siglaUf'] = $request->get('siglaUf');
        }

        // Gera uma chave única para o filtro atual
        $cacheKey = 'despesas_api_' . md5(json_encode($filter));

        // Tenta recuperar do cache, senão busca da API e salva por 10 minutos (600 segundos)
        $despesas = Cache::remember($cacheKey, 600, function() use($filter) {
            return $this->deputadoApiService->all($filter);
        });

        $links = collect($despesas['links'])->keyBy('rel');

        return view('despesas/home', [
            'despesas' => $despesas['dados'] ?? [],
            'filter' => $filter,
            'links' => $links,
        ]);
    }

    public function sync(DeputadoRequest $request)
    {
        $data = $request->validated();

        $this->despesaService->create($data);

        if ($this->despesaService->status() === 'error') {
            return redirect()->back()->with('error', $this->despesaService->message());
        }

        return redirect()->back()->with('success', 'Deputado sincronizado com sucesso.');
    }

    public function index(Request $request)
    {
         $filter = [
            'pagina' => $request->get('pagina', 1),
            'ordem' => $request->get('ordem', 'ASC'),
            'ordenar_por' => $request->get('ordenarPor', 'nome'),
        ];

        if ($request->filled('nome')) {
            $filter['nome'] = $request->get('nome');
        }

        if ($request->filled('siglaPartido')) {
            $filter['sigla_partido'] = $request->get('siglaPartido');
        }

        if ($request->filled('siglaUf')) {
            $filter['sigla_uf'] = $request->get('siglaUf');
        }

        $pearPage = $request->get('itens', 5);

        $columns = ['id', 'nome', 'url_foto', 'sigla_uf', 'sigla_partido'];

        // Gera uma chave única para o filtro atual
        $cacheKey = 'despesas_db_' . md5(json_encode($filter));

        // Tenta recuperar do cache, senão busca no MySQL e salva por 10 minutos (600 segundos)
        $despesas = Cache::remember($cacheKey, 600, function() use($filter, $pearPage, $columns) {
            return $this->despesaService->getAll($filter, $pearPage, $columns);
        });

        if ($despesas->status() === 'error') $despesas = [];

        // Armazena a URL atual para redirecionar o usuário de volta após uma ação
        session()->put('despesas_back_url', url()->full());

        return view('despesas.index', [
            'filter' => $filter,
            'despesas' => $despesas,
        ]);
    }

    public function show($id = null)
    {
        $deputado = $this->despesaService->getById($id);
        $backUrl = session('despesas_back_url', route('despesas.index'));

        if ($this->despesaService->status() === 'error') $deputado = [];

        return view('despesas.show', [
            'deputado' => $deputado ?? [],
            'backUrl' => $backUrl
        ]);
    }

    public function destroy($id = null)
    {
        $deputado = $this->despesaService->delete($id);
        $backUrl = session('despesas_back_url', route('despesas.index'));

        if ($this->despesaService->status() === 'error') {
            return redirect()->back()->with('error', $this->despesaService->message());
        }

        return redirect()->back()->with('success', 'Deputado removido com sucesso.');
    }
}
