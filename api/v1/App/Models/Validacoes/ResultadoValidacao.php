<?php

namespace App\Models\Validacoes;

use App\Models\Validacoes\Validacao;
use InvalidArgumentException;

class ResultadoValidacao 
{
    private array $validacoes = array();
    
    public function adicionarValidacao(Validacao $validacao)
    {
        if (isset($validacao) && $validacao->temMensagens()) {
            $this->validacoes[] = $validacao;
        }
    }

    public function adicionarValidacoes(array $validacoes)
    {
        foreach($validacoes as $validacao) {
            if (get_class($validacao) != "App\\Models\\Validacoes\\Validacao") {
                throw new InvalidArgumentException("O paramêtro desta função deve ser uma lista de objetos do tipo 'Validacao'. ". get_class($validacao) ." recebido.");
            }
            $this->adicionarValidacao($validacao);
        }
    }

    public function temErros() : bool
    {
        return count($this->validacoes) > 0;
    }

    public function resultadosEmJson()
    {
        $resultado = array();
        foreach($this->validacoes as $validacao) {
            $resultado["errors"][$validacao->getParametro()] = $validacao->getMensagens();
        }
        return $resultado;
    }
}