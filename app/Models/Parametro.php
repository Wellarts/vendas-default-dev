<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_empresa',
        'endereco_completo',
        'telefone',
        'email',
        'cnpj',
        'redes_sociais',
        'logo',
        'versao_sistema',
        'data_atualizacao',
        'detalhe_atualizacao',
        'ativo',
        'notificar_usuario',
        'catalogo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}