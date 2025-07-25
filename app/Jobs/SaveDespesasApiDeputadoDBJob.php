<?php

namespace App\Jobs;

use App\Services\DespesaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveDespesasApiDeputadoDBJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $data = [
            'deputado_id'        => $this->data['deputado_id'],
            'ano'                => $this->data['ano'],
            'mes'                => $this->data['mes'],
            'tipo_despesa'       => $this->data['tipoDespesa'] ?? null,
            'cod_documento'      => $this->data['codDocumento'] ?? null,
            'tipo_documento'     => $this->data['tipoDocumento'] ?? null,
            'data_documento'     => $this->data['dataDocumento']
                                    ? date('Y-m-d', strtotime($this->data['dataDocumento']))
                                    : null,
            'num_documento'      => $this->data['numDocumento'] ?? null,
            'valor_documento'    => $this->data['valorDocumento'] ?? null,
            'valor_glosa'        => $this->data['valorGlosa'] ?? null,
            'valor_liquido'      => $this->data['valorLiquido'] ?? null,
            'nome_fornecedor'    => $this->data['nomeFornecedor'] ?? null,
            'cnpj_cpf_fornecedor'=> $this->data['cnpjCpfFornecedor'] ?? null,
            'parcela'            => $this->data['parcela'] ?? null,
            'url_documento'      => $this->data['urlDocumento'] ?? null,
        ];

        app(DespesaService::class)->create($data);
    }
}
