<?php

namespace App\Models\Denuncia;

use App\Models\Validacoes\ResultadoValidacao;
use App\Models\Validacoes\Validacao;
use App\Utils\ValidacaoUtil;

class Comentario
{
    public ?int $id;
    public int $usuarioId;
    public int $denunciaId;
    public String $autor;
    public String $comentario;
    public \DateTime $dataPublicacao;
    public \DateTime $dataAtualizacao;
    public bool $doMinisterioDaSaude;

    public function validarCadastro() : ResultadoValidacao
    {
        $resultadoValidacao = new ResultadoValidacao();
        $resultadoValidacao->adicionarValidacao($this->validarComentario());
        return $resultadoValidacao;
    }

    public function validarAtualizacao() : ResultadoValidacao
    {
        return $this->validarCadastro();
    }

    private function validarComentario() : Validacao
    {
        $validacao = new Validacao("comentario");
        $result = ValidacaoUtil::tamanhoEntre($this->comentario, 10, 2000);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O comentário deve ter entre 10 e 2000 caracteres"));
        return $validacao;
    }
}