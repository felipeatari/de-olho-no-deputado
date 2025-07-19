<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Despesa extends Model
{
    protected $table = 'despesas';

    protected $fillable = [
        'deputado_id',
        'ano',
        'mes',
        'tipo_despesa',
        'cod_documento',
        'tipo_documento',
        'data_documento',
        'num_documento',
        'valor_documento',
        'valor_glosa',
        'valor_liquido',
        'nome_fornecedor',
        'cnpj_cpf_fornecedor',
        'parcela',
        'url_documento',
        'data_cadastro',
    ];

    public function deputado(): BelongsTo
    {
        return $this->belongsTo(Deputado::class, 'deputado_id');
    }
}
