<?php

namespace App\Http\Controllers;

use App\Jobs\SearchDespesasApiDeputadoJob;
use App\Services\DespesaApiService;
use App\Services\DespesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DespesaController extends Controller
{
    public function __construct(
        protected DespesaService $despesaService,
        protected DespesaApiService $despesaApiService,
    )
    {
    }

    public function sync(Request $request)
    {
        $deputadoId = $request->get('deputado_id', null);

        if (!Cache::lock("importando-despesas-{$deputadoId}", 60)->get()) {
            return redirect()->back()->with('warning', 'A sincronização deste deputado já está em andamento.');
        }

        SearchDespesasApiDeputadoJob::dispatch($deputadoId);

        Cache::tags('despesas_db')->flush();

        return redirect()->back()->with('success', 'Sincronização iniciada. As despesas serão processadas em segundo plano.');
    }

    public function index(Request $request)
    {
        $filter = [
            'pagina' => $request->get('pagina', 1),
            'ordem' => $request->get('ordem', 'ASC'),
        ];

        if ($request->filled('nome')) {
            $filter['nome'] = $request->get('nome');
        }

        if ($request->filled('deputado')) {
            $filter['deputado'] = $request->get('deputado');
        }

        if ($request->filled('tipo_despesa')) {
            $filter['tipo_despesa'] = $request->get('tipo_despesa');
        }

        if ($request->filled('valor_documento')) {
            $filter['valor_documento'] = $request->get('valor_documento');
        }

        $pearPage = $request->get('itens', 10);

        $columns = ['id', 'deputado', 'tipo_despesa', 'valor_documento'];

        // Gera uma chave única para o filtro atual
        $cacheKey = 'despesas_db_' . md5(json_encode($filter));

        // Tenta recuperar do cache, senão busca no MySQL e salva por 10 minutos (600 segundos)
        // $despesas = Cache::tags('despesas_db')->remember($cacheKey, 600, function() use($filter, $pearPage) {
        //     return $this->despesaService->getAll($filter, $pearPage);
        // });
        $despesas = $this->despesaService->getAll($filter, $pearPage);

        // Armazena a URL atual para redirecionar o usuário de volta após uma ação
        session()->put('despesas_back_url', url()->full());

        return view('despesas.index', [
            'filter' => $filter,
            'despesas' => $despesas->data() ?? [],
        ]);
    }

    public function show($id = null)
    {
        $despesa = $this->despesaService->getById($id);
        $backUrl = session('despesas_back_url', route('despesas.index'));

        if ($despesa->status() === 'error') {
            return redirect($backUrl);
        }

        return view('despesas.show', [
            'despesa' => $despesa->data(),
            'backUrl' => $backUrl
        ]);
    }

    public function destroy($id = null)
    {
        $despesa = $this->despesaService->delete($id);
        $backUrl = session('despesas_back_url', route('despesas.index'));

        if ($this->despesaService->status() === 'error') {
            return redirect()->back()->with('error', $this->despesaService->message());
        }

        Cache::tags('despesas_db')->flush();

        return redirect()->back()->with('success', 'Despesa removido com sucesso.');
    }
}
