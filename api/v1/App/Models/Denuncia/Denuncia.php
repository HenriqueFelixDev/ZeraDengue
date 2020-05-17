<?php

namespace App\Models\Denuncia;

use App\Models\Validacoes\ResultadoValidacao;
use App\Models\Validacoes\Validacao;
use App\Utils\ValidacaoUtil;
use DateTime;

class Denuncia
{
    public const AGUARDANDO_ANALISE = 'aguardando_analise';
    public const SOB_ANALISE = 'sob_analise';
    public const RESOLVIDA = 'resolvida';

    public ?int $id;
    public int $usuarioId;
    public String $assunto;
    public String $descricao;
    public ?array $fotos;
    public CoordenadasFoco $coordenadasFoco;
    public String $localFoco;
    public DateTime $dataPublicacao;
    public String $situacao;
    public array $comentarios;

    public function __construct()
    {
        $this->coordenadasFoco = new CoordenadasFoco();
    }

    public function validarCadastro() : ResultadoValidacao
    {
        $resultadoValidacao =  new ResultadoValidacao();
        $resultadoValidacao->adicionarValidacao($this->validarAssunto());
        $resultadoValidacao->adicionarValidacao($this->validarDescricao());
        $resultadoValidacao->adicionarValidacao($this->validarFotos());
        $resultadoValidacao->adicionarValidacao($this->validarLocalFoco());
        $resultadoValidacao->adicionarValidacao($this->validarCoordenadasFoco());
        $resultadoValidacao->adicionarValidacao($this->validarSituacao());
        
        return $resultadoValidacao;
    }

    private function validarAssunto() : Validacao
    {
        $validacao = new Validacao("assunto");
        $result = ValidacaoUtil::tamanhoEntre($this->assunto, 8, 128);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O Assunto precisa ter entre 8 e 128 caracteres"));
        return $validacao;
    }

    private function validarDescricao() : Validacao
    {
        $validacao = new Validacao("descricao");
        $result = ValidacaoUtil::tamanhoEntre($this->descricao, 16, 2000);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "A descrição precisa ter entre 16 e 2000 caracteres"));
        return $validacao;
    }

    private function validarFotos() : Validacao
    {
        $validacao = new Validacao("fotos");
        
        if (!isset($this->fotos) || count($this->fotos["tmp_name"]) > 10) {
            $validacao->adicionarMensagem("A denúncia deve ter entre 1 e 10 fotos do foco da dengue");
        } else {
            for ($i = 0; $i < count($this->fotos["tmp_name"]); $i++) {
                switch ($this->fotos["error"][$i]) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $validacao->adicionarMensagem("A fotos da denúncia devem ter o tamanho máximo de 2MB");
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $validacao->adicionarMensagem("A denúncia deve ter entre 1 e 10 fotos do foco da dengue");
                        break;
                    case UPLOAD_ERR_OK:
                        break;
                    default:
                        $validacao->adicionarMensagem("Ocorreu um erro interno no servidor e as fotos não foram enviadas");
                }
            }
        }

        return $validacao;
    }

    private function validarLocalFoco()
    {
        $validacao = new Validacao("local_foco");
        $result = ValidacaoUtil::tamanhoEntre($this->localFoco, 5, 256);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O local do foco informado é inválido. Ele precisa ter entre 5 e 256 caracteres"));
        return $validacao;
    }

    private function validarCoordenadasFoco() : Validacao
    {
        $validacao = new Validacao("coordenadas");
        $result1 = ValidacaoUtil::valorEntre($this->coordenadasFoco->latitude, -180.0, 180.0);
        $result2 = ValidacaoUtil::valorEntre($this->coordenadasFoco->longitude, -180.0, 180.0);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result1 || $result2, "A coordenada geográfica informada é inválida"));
        return $validacao;
    }

    private function validarSituacao() : Validacao
    {
        $validacao = new Validacao("situacao");
        $result = $this->situacao == self::AGUARDANDO_ANALISE || 
                    $this->situacao == self::AGUARDANDO_ANALISE || 
                    $this->situacao == self::AGUARDANDO_ANALISE;
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "A situação {$this->situacao} é inválida"));
        return $validacao;
    }
}