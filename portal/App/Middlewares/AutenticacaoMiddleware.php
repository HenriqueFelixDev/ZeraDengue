<?php

namespace App\Middlewares;

use App\Controllers\AutenticacaoController;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use Pecee\SimpleRouter\SimpleRouter;

require_once "vendor/pecee/simple-router/helpers.php";

class AutenticacaoMiddleware implements IMiddleware
{
    private AutenticacaoController $autenticacaoController;

    public function __construct()
    {
        $this->autenticacaoController = new AutenticacaoController();
    }

    /**
     * @Override
     */
    public function handle(Request $request): void
    {   
        $autenticado = array_key_exists("autenticacao", $_SESSION) && $_SESSION["autenticacao"] != null && $this->autenticacaoController->validar();
        $rotaParaLogin = preg_match('/login\/?$/', SimpleRouter::getUrl()->getPath());
        
        if (!$autenticado && !$rotaParaLogin) {
            SimpleRouter::response()->redirect(url("usuario.login"));
        }

        if($autenticado && $rotaParaLogin) {
            SimpleRouter::response()->redirect(url("home"));
        }
    }
}