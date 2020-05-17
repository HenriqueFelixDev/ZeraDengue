<?php

namespace App\Models\Mensagens;

class Mensagem
{

    public const TIPO_ERRO = "error";
    public const TIPO_SUCESSO = "success";
    public const TIPO_INFO = "info";
    
    public String $campo;
    public String $mensagem;
    public String $tipo;

    public function __construct(String $campo, String $mensagem, String $tipo = self::TIPO_INFO)
    {
        $this->campo    = $campo;
        $this->mensagem = $mensagem;
        $this->tipo     = $tipo;
    }
}