<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeputadoRequest;
use App\Services\DeputadoApiService;
use App\Services\DeputadoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class DeputadoController extends Controller
{
    public function __construct(
        protected DeputadoService $deputadoService,
        protected DeputadoApiService $deputadoApiService,
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
        $cacheKey = 'deputados_api_' . md5(json_encode($filter));

        // Tenta recuperar do cache, senão busca na API e salva por 10 minutos (600 segundos)
        if (!Cache::has($cacheKey)) {
            $deputados = $this->deputadoApiService->all($filter);

            if ($this->deputadoApiService->status() === 'error') {
                return redirect()->back()->with('error', $this->deputadoApiService->message());
            }

            Cache::put($cacheKey, $deputados, 600);
        } else {
            $deputados = Cache::get($cacheKey);
        }

        $links = collect($deputados['links'])->keyBy('rel');

        return view('deputados/home', [
            'deputados' => $deputados['dados'] ?? [],
            'filter' => $filter,
            'links' => $links,
        ]);
    }

    public function sync(DeputadoRequest $request)
    {
        $data = $request->validated();

        $this->deputadoService->create($data);

        if ($this->deputadoService->status() === 'error') {
            return redirect()->back()->with('error', $this->deputadoService->message());
        }

        Cache::tags('deputados_db')->flush();

        return redirect()->back()->with('success', 'Deputado sincronizado com sucesso.');
    }

    public function index(Request $request)
    {
         $filter = [
            'pagina' => $request->get('pagina', 1),
            'ordem' => $request->get('ordem', 'ASC'),
            'ordenar_por' => $request->get('ordenar_por', 'nome'),
        ];

        if ($request->filled('nome')) {
            $filter['nome'] = $request->get('nome');
        }

        if ($request->filled('sigla_partido')) {
            $filter['sigla_partido'] = $request->get('sigla_partido');
        }

        if ($request->filled('sigla_uf')) {
            $filter['sigla_uf'] = $request->get('sigla_uf');
        }

        $pearPage = $request->get('itens', 5);

        $columns = ['id', 'nome', 'url_foto', 'sigla_uf', 'sigla_partido'];

        // Gera uma chave única para o filtro atual
        $cacheKey = 'deputados_db_' . md5(json_encode($filter));

        // Tenta recuperar do cache, senão busca no MySQL e salva por 10 minutos (600 segundos)
        $deputados = Cache::tags('deputados_db')->remember($cacheKey, 600, function() use($filter, $pearPage, $columns) {
            return $this->deputadoService->getAll($filter, $pearPage, $columns);
        });

        // Armazena a URL atual para redirecionar o usuário de volta após uma ação
        session()->put('deputados_back_url', url()->full());

        return view('deputados.index', [
            'filter' => $filter,
            'deputados' => $deputados->data() ?? [],
        ]);
    }

    public function show($id = null)
    {
        $deputado = $this->deputadoService->getById($id);
        $backUrl = session('deputados_back_url', route('deputados.index'));

        if ($deputado->status() === 'error') {
            return redirect($backUrl);
        }

        return view('deputados.show', [
            'deputado' => $deputado->data(),
            'backUrl' => $backUrl
        ]);
    }

    public function destroy($id = null)
    {
        $deputado = $this->deputadoService->delete($id);
        $backUrl = session('deputados_back_url', route('deputados.index'));

        if ($this->deputadoService->status() === 'error') {
            return redirect()->back()->with('error', $this->deputadoService->message());
        }

        Cache::tags('deputados_db')->flush();

        return redirect()->back()->with('success', 'Deputado removido com sucesso.');
    }
}
