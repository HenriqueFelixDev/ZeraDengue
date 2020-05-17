<?php

namespace App\Controllers;

use App\Models\Mensagens\GerenciadorMensagens;
use App\Models\Mensagens\Mensagem;
use App\Repositories\UsuarioRepository;
use Pecee\SimpleRouter\SimpleRouter;

require_once "vendor/pecee/simple-router/helpers.php";

class UsuarioController extends Controller
{
    private UsuarioRepository $usuarioRepository;

    public function __construct()
    {
        $this->usuarioRepository = new UsuarioRepository();
    }

    public function conta()
    {
        global $twig;
        $dados = $this->obterDados();
        $mensagens = GerenciadorMensagens::obterMensagens();
        echo $twig->render("usuario/minha_conta.php", ["dados" => $dados, "mensagens" => $mensagens]);
        GerenciadorMensagens::deletarMensagens();
    }

    public function cadastro()
    {
        global $twig;
        $mensagens = GerenciadorMensagens::obterMensagens();
        echo $twig->render("usuario/cadastro.php", ["mensagens" => $mensagens]);
        GerenciadorMensagens::deletarMensagens();
    }

    public function cadastrarOuAtualizar()
    {
        $dados = SimpleRouter::request()->getInputHandler();

        $usuario = [
            "nome" => trim($dados->value("nome")),
            "email" => $dados->value("email"),
            "senha" => $dados->value("senha"),
            "cpf" => $dados->value("cpf"),
            "rg" => $dados->value("rg"),
            "endereco_id" => $dados->value("endereco_id"),
            "cep" => $dados->value("cep"),
            "logradouro" => $dados->value("logradouro"),
            "numero" => $dados->value("numero"),
            "bairro" => $dados->value("bairro"),
            "cidade" => $dados->value("cidade"),
            "estado" => $dados->value("estado"),
            "complemento" => $dados->value("complemento"),
            "telefones_ids" => $dados->value("telefone_id"),
            "ddds" => $dados->value("ddd"),
            "telefones" => $dados->value("tel"),
            "telefones_removidos" => $dados->value("telefones_removidos")
        ];

        $urlRedirect = "usuario.cadastro";
        if(!array_key_exists("autenticacao", $_SESSION) || !isset($_SESSION["autenticacao"])) {
            $result = $this->usuarioRepository->cadastrar($usuario);
        } else {
            $result = $this->usuarioRepository->atualizar($usuario);
            $urlRedirect = "usuario.conta";
        }

        parent::salvarMensagensDeErro($result, "usuario_status");
        if (!isset($result->errors)) {
            GerenciadorMensagens::salvarMensagem(new Mensagem("usuario_status", "Usuário salvo com sucesso!", Mensagem::TIPO_SUCESSO));
        }
        
        SimpleRouter::response()->redirect(url($urlRedirect));
    }

    public function obterDados()
    {
        return $this->usuarioRepository->obterDados();
    }
}