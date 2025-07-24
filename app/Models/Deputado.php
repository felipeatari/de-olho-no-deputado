<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deputado extends Model
{
    public $incrementing = false; // porque o ID vem da API externa
    protected $keyType = 'int';

    protected $table = 'deputados';

    protected $fillable = [
        'id',
        'uri',
        'nome',
        'sigla_partido',
        'uri_partido',
        'sigla_uf',
        'id_legislatura',
        'url_foto',
        'email',
    ];

    public function despesas(): HasMany
    {
        return $this->hasMany(Despesa::class, 'deputado_id');
    }
}
