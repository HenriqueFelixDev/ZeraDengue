<?php

namespace App\Models\Validacoes;

class Validacao 
{
    private String $parametro;
    private array $mensagens;

    public function __construct(String $parametro) 
    {
        $this->parametro = $parametro;
        $this->mensagens = array();
    }

    public function temMensagens() : bool 
    {
        return count($this->mensagens) > 0;
    }

    public function adicionarMensagem(?String $mensagem) : void
    {
        if (isset($mensagem)) {
            $this->mensagens[] = $mensagem;
        }
    }

    public function getParametro() : String
    {
        return $this->parametro;
    }

    public function getMensagens() : array
    {
        return $this->mensagens;
    }
}