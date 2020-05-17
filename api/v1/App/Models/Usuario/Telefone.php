<?php

namespace App\Models\Usuario;

use App\Models\Validacoes\Validacao;
use App\Utils\ValidacaoUtil;

class Telefone
{
    public ?int $id;
    public int $usuarioId;
    public ?int $ddd;
    public int $telefone;

    public function __construct(?int $id, int $usuarioId, ?int $ddd, int $telefone)
    {
        $this->id = $id;
        $this->usuarioId = $usuarioId;
        $this->ddd = $ddd;
        $this->telefone = $telefone;
    }

    public function validarTelefone() : Validacao
    {
        $validacao = new Validacao("telefone");
        $result = ValidacaoUtil::telefone($this->ddd.$this->telefone);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O telefone ({$this->ddd}) {$this->telefone} é inválido"));
        
        return $validacao;
    }
}