<?php

namespace App\Jobs;

use App\Services\DespesaApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SearchDespesasApiDeputadoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $deputadoId)
    {
    }

    public function handle(): void
    {
        $despesaApiService = app(DespesaApiService::class);

        $pagina = 1;
        $itens = 50;

        do {
            $filter = [
                'pagina' => $pagina,
                'itens' => $itens,
            ];

            $resposta = $despesaApiService->all($filter, $this->deputadoId);
            $despesas = $resposta['dados'] ?? [];
            $links = $resposta['links'] ?? [];

            foreach ($despesas as $despesa):
                $despesa['deputado_id'] = $this->deputadoId;
                SaveDespesasApiDeputadoDBJob::dispatch($despesa);
            endforeach;

            $temProximaPagina = collect($links)->contains(fn ($link) => $link['rel'] === 'next');
            $pagina++;

        } while ($temProximaPagina);
    }
}
