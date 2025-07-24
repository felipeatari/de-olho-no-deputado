<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeputadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer'],
            'uri' => ['required', 'url'],
            'nome' => ['required', 'string', 'max:255'],
            'sigla_partido' => ['required', 'string', 'max:30'],
            'uri_partido' => ['required', 'url'],
            'sigla_uf' => ['required', 'string', 'size:2'],
            'id_legislatura' => ['required', 'integer'],
            'url_foto' => ['required', 'url'],
            'email' => ['nullable', 'email'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O ID do deputado é obrigatório.',
            'id.integer' => 'O ID do deputado deve ser um número inteiro.',

            'uri.required' => 'A URI do deputado é obrigatória.',
            'uri.url' => 'A URI do deputado deve ser uma URL válida.',

            'nome.required' => 'O nome do deputado é obrigatório.',
            'nome.string' => 'O nome do deputado deve ser um texto.',
            'nome.max' => 'O nome do deputado não pode ter mais que 255 caracteres.',

            'sigla_partido.required' => 'A sigla do partido é obrigatória.',
            'sigla_partido.string' => 'A sigla do partido deve ser um texto.',
            'sigla_partido.max' => 'A sigla do partido não pode ter mais que 10 caracteres.',

            'uri_partido.required' => 'A URI do partido é obrigatória.',
            'uri_partido.url' => 'A URI do partido deve ser uma URL válida.',

            'sigla_uf.required' => 'A sigla da UF é obrigatória.',
            'sigla_uf.string' => 'A sigla da UF deve ser um texto.',
            'sigla_uf.size' => 'A sigla da UF deve ter exatamente 2 caracteres.',

            'id_legislatura.required' => 'O ID da legislatura é obrigatório.',
            'id_legislatura.integer' => 'O ID da legislatura deve ser um número inteiro.',

            'url_foto.required' => 'A URL da foto é obrigatória.',
            'url_foto.url' => 'A URL da foto deve ser uma URL válida.',

            'email.email' => 'O e-mail deve ser um endereço válido.',
        ];
    }
}
