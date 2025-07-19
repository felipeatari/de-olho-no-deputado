<?php

namespace App\Http\Controllers;

use App\Services\DeputadoService;
use Illuminate\Http\Request;

class DeputadoController extends Controller
{
    public function __construct(
        protected DeputadoService $deputadoService,
    )
    {
    }

    public function index(Request $request)
    {
        $filter = $request->all();
        $perPage = $request->get('per_page', 5);
        $columns = [];
        $columns = ['id', 'nome'];

        $data = $this->deputadoService->getAll($filter, $perPage, $columns);
        dd($data);
        return response()->json($data);

        // if ($this->sortition->status() === 'error') $data = [];

        // return view('affiliate.index', [
        //     'deputados' => $data ?? [],
        // ]);
    }
}
