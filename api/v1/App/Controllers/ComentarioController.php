<?php

namespace App\Controllers;

use App\Models\DB;
use App\Models\Denuncia\Comentario;
use App\Repositories\IComentarioRepository;
use App\Repositories\MySQL\ComentarioRepositoryMySQL;
use DateTime;
use Pecee\SimpleRouter\SimpleRouter;

class ComentarioController
{

    private IComentarioRepository $comentarioRepository;

    public function __construct()
    {
        $this->comentarioRepository = new ComentarioRepositoryMySQL(DB::obterConexao());
    }

    public function index(int $denunciaId, int $comentarioId = null)
    {
        if(!isset($comentarioId)) {
            switch (SimpleRouter::request()->getMethod()) {
                case "get":
                    $this->obterComentarios($denunciaId);
                    break;

                case "post":
                    $this->postarComentario($denunciaId);
                    break;
            }
            exit;
        }

        switch (SimpleRouter::request()->getMethod()) {
            case "get":
                $this->obterComentarioPorId($denunciaId, $comentarioId);
                break;

            case "patch":
                $this->atualizarComentario($denunciaId, $comentarioId);
                break;

            case "delete":
                $this->deletarComentario($denunciaId, $comentarioId);
                break;
        }
    }

    public function postarComentario(int $denunciaId) : void
    {
        $comentario = $this->extrairDadosComentario($denunciaId);
        $resultadoValidacao = $comentario->validarCadastro();

        if ($resultadoValidacao->temErros()) {
            SimpleRouter::response()->httpCode(422)->json($resultadoValidacao->resultadosEmJson());
        }

        $result = $this->comentarioRepository->cadastrar($comentario);

        if (!$result) {
            SimpleRouter::response()->httpCode(500)->json([
                "errors" => "Erro ao salvar o comentário"
            ]);
        }
    }

    public function atualizarComentario($denunciaId, $comentarioId) : void
    {
        $comentario = $this->extrairDadosComentario($denunciaId, $comentarioId);
        $comentario->dataAtualizacao = new DateTime();

        $resultadoValidacao = $comentario->validarAtualizacao();

        if ($resultadoValidacao->temErros()) {
            SimpleRouter::response()->httpCode(422)->json($resultadoValidacao->resultadosEmJson());
        }
        
        $result = $this->comentarioRepository->atualizar($comentario);

        if (!$result) {
            SimpleRouter::response()->httpCode(500)->json([
                "errors" => "Erro ao atualizar o comentário"
            ]);
        }
    }

    public function deletarComentario(int $denunciaId, int $comentarioId) : void
    {   
        $result = $this->comentarioRepository->deletar($comentarioId);
        
        if (!$result) {
            SimpleRouter::response()->httpCode(500)->json([
                "errors" => "Erro ao deletar o comentário"
            ]);
        }
    }

    public function obterComentarios(int $denunciaId)
    {
        $url = SimpleRouter::request()->getUrl();
        $comentarios = $this->comentarioRepository->obterComentariosDaDenuncia($denunciaId);

        $comentariosDados = array();
        foreach($comentarios as $comentario) {
            $comentariosDados[] = $this->obterHATEOASComentario($comentario);
        }

        $comentarioInfo = array();
        $comentarioInfo["totalItens"] = count($comentarios);
        $comentarioInfo["self"] = $url;
        $comentarioInfo["itens"] = $comentariosDados;
        SimpleRouter::response()->json($comentarioInfo);
    }

    public function obterComentarioPorId(int $denunciaId, int $comentarioId)
    {
        $comentario = $this->comentarioRepository->obterPorId($comentarioId);

        if(!isset($comentario)) {
            SimpleRouter::response()->httpCode(404)->json([
                "errors" => "Comentário não encontrado"
            ]);
        }

        $comentarioDados = $this->obterHATEOASComentario($comentario, $comentarioId);

        return SimpleRouter::response()->json($comentarioDados);

    }

    private function extrairDadosComentario(int $denunciaId, int $comentarioId = null)
    {
        $dados = SimpleRouter::request()->getInputHandler();

        $comentario = new Comentario();
        $comentario->id = $comentarioId;
        $comentario->usuarioId = SimpleRouter::request()->user->id;
        $comentario->denunciaId = $denunciaId;
        $comentario->comentario = filter_var($dados->value("comentario"), FILTER_SANITIZE_STRING);
        $comentario->dataPublicacao = new DateTime();

        return $comentario;
    }

    private function obterHATEOASComentario($comentario, $comentarioId = null)
    {
        $comentarioUrl  = SimpleRouter::request()->getUrl();
        $comentarioUrl .= isset($comentarioId) ? "" : $comentario->comentario_id;

        $comentarioDados = [
            "self" => $comentarioUrl,
            "links" => [
                "patch" => $comentarioUrl,
                "delete" => $comentarioUrl
            ],
            "dados" => $comentario
        ];

        return $comentarioDados;
    }
    
}