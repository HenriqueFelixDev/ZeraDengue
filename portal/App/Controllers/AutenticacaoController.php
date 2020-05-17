<?php

namespace App\Controllers;

use App\Repositories\AutenticacaoRepository;
use Pecee\SimpleRouter\SimpleRouter;
use App\Models\Mensagens\GerenciadorMensagens;

class AutenticacaoController extends Controller
{
    private AutenticacaoRepository $autenticacaoRepository;

    public function __construct()
    {
        $this->autenticacaoRepository = new AutenticacaoRepository();
    }

    public function login()
    {
        global $twig;
        $mensagens = GerenciadorMensagens::obterMensagens();
        echo $twig->render("usuario/login.php", ["mensagens" => $mensagens]);
        GerenciadorMensagens::deletarMensagens();
    }

    /**
     * @Action
     */
    public function logar(String $email, String $senha)
    {
        $result = $this->autenticacaoRepository->autenticar($email, $senha);
        parent::salvarMensagensDeErro($result, "login");
        
        if (!isset($result->errors)) {
            $_SESSION["autenticacao"] = $result;
        }
        SimpleRouter::response()->redirect(BASE_URL."/");
    }

    /**
     * @Action
     */
    public function validar()
    {
        $result = $this->autenticacaoRepository->validarAutenticacao();
        return $result->result;
    }

    /**
     * @Action
     */
    public function deslogar()
    {
        unset($_SESSION["autenticacao"]);
        session_destroy();
        SimpleRouter::response()->redirect(BASE_URL."/login");
    }

}