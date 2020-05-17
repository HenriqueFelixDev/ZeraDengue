<?php

namespace App\Controllers;

use App\Models\Mensagens\GerenciadorMensagens;
use App\Models\Mensagens\Mensagem;
use App\Repositories\ComentarioRepository;
use Pecee\SimpleRouter\SimpleRouter;

require_once "vendor/pecee/simple-router/helpers.php";

class ComentarioController extends Controller
{
    private ComentarioRepository $comentarioRepository;

    public function __construct()
    {
        $this->comentarioRepository = new ComentarioRepository();
    }

    public function cadastrarOuAtualizarComentario(int $denunciaId, int $comentarioId = null)
    {
        $comentario = filter_var(SimpleRouter::request()->getInputHandler()->value("comentario"), FILTER_SANITIZE_STRING);
        
        $acaoComentario = "cadastrado";
        if ($comentarioId == null) {
            $result = $this->comentarioRepository->cadastrar($denunciaId, $comentario);
        } else {
            $result = $this->comentarioRepository->atualizar($denunciaId, $comentarioId, $comentario);
            $acaoComentario = "atualizado";
        }

        parent::salvarMensagensDeErro($result, "comentario_status");
        if (!isset($result->errors)) {
            GerenciadorMensagens::salvarMensagem(new Mensagem("comentario_status", "Comentário $acaoComentario com sucesso!", Mensagem::TIPO_ERRO));
        }
        
        SimpleRouter::response()->redirect(BASE_URL."/denuncias/$denunciaId");
    }

    public function deletarComentario(int $denunciaId, int $comentarioId)
    {
        $result = $this->comentarioRepository->deletar($denunciaId, $comentarioId);
        
        parent::salvarMensagensDeErro($result, "comentario_status");
        if (!isset($result->errors)) {
            GerenciadorMensagens::salvarMensagem(new Mensagem("comentario_status", "Comentário deletado com sucesso", Mensagem::TIPO_SUCESSO));
        }

        SimpleRouter::response()->redirect(BASE_URL."/denuncias/$denunciaId");
        
    }
}