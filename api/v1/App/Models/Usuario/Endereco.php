<?php

namespace App\Models\Usuario;

use App\Models\Validacoes\Validacao;
use App\Utils\ValidacaoUtil;

class Endereco
{
    public int $id;
    public int $usuarioId;
    public String $logradouro;
    public String $bairro;
    public int $cep;
    public String $cidade;
    public String $estado;
    public String $numero;
    public ?String $complemento;

    public function __construct(?int $id = null, ?int $usuarioId = null, ?String $logradouro = null, ?String $bairro = null, ?int $cep = null, ?String $cidade = null, ?String $estado = null, ?String $numero = null, ?String $complemento)
    {
        $this->id = $id;
        $this->usuarioId = $usuarioId;
        $this->logradouro = $logradouro;
        $this->bairro = $bairro;
        $this->cep = $cep;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->numero = $numero;
        $this->complemento = $complemento;
    }

    public function validarCadastro() : array
    {
        return [
            $this->validarLogradouro(),
            $this->validarBairro(),
            $this->validarCEP(),
            $this->validarCidade(),
            $this->validarEstado(),
            $this->validarNumero(),
            $this->validarComplemento(),
        ];
    }

    public function validarAtualizacao() : array
    {
        return $this->validarCadastro();
    }

    private function validarLogradouro() : Validacao
    {
        $validacao = new Validacao("logradouro");
        $result = ValidacaoUtil::tamanhoEntre($this->logradouro, 3, 128);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O Logradouro precisa ter entre 3 e 128 caracteres"));
        return $validacao;
    }

    private function validarBairro() : Validacao
    {
        $validacao = new Validacao("bairro");
        $result = ValidacaoUtil::tamanhoEntre($this->bairro, 3, 64);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O Bairro precisa ter entre 3 e 64 caracteres"));
        return $validacao;
    }

    private function validarCEP() : Validacao
    {
        $validacao = new Validacao("cep");
        $result = ValidacaoUtil::cep($this->cep);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O CEP {$this->cep} é inválido"));
        return $validacao;
    }

    private function validarCidade() : Validacao
    {
        $validacao = new Validacao("cidade");
        $result = ValidacaoUtil::tamanhoEntre($this->cidade, 3, 64);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "A Cidade deve ter entre 3 e 64 caracteres"));
        return $validacao;
    }

    private function validarEstado() : Validacao
    {
        $validacao = new Validacao("estado");
        $result1 = ValidacaoUtil::tamanhoEntre($this->estado, 2, 2);
        $result2 = ValidacaoUtil::somenteLetras($this->estado);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result1, "A sigla do Estado possui somente 2 caracteres"));
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result2, "O Estado deve possuir apenas letras"));
        return $validacao;
    }

    private function validarNumero() : Validacao
    {
        $validacao = new Validacao("numero");
        $result = ValidacaoUtil::tamanhoEntre($this->numero, 1, 8);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O Número deve ter entre 1 e 8 caracteres"));
        return $validacao;
    }

    private function validarComplemento() : Validacao
    {
        $validacao = new Validacao("complemento");

        $result = true;
        if (!empty($this->complemento)) {
            $result = ValidacaoUtil::tamanhoMax($this->complemento, 128);
        }
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O Complemento não pode ser maior que 128 caracteres"));
        return $validacao;
    }
}