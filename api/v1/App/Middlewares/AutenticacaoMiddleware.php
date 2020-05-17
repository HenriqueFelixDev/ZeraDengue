<?php

namespace App\Middlewares;

use App\Models\Token\JWTToken;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use App\Models\Usuario\Usuario;
use Pecee\SimpleRouter\SimpleRouter;

class AutenticacaoMiddleware implements IMiddleware
{
    /**
     * @Override
     */
    public function handle(Request $request): void
    {
        $request = SimpleRouter::request();
        if (preg_match('/usuarios\\/$/', $request->getUrl()) && $request->getMethod() == "post") {
            return;
        }

        $jwt = new JWTToken();
        $headers = apache_request_headers();
        $authorization = array_key_exists("Authorization", $headers) ? $headers["Authorization"] : '';
        $token = str_replace("Bearer ", "", $authorization);
        $result = $jwt->validarToken($token);

        if ($result) {
            $dados = $jwt->obterDados($token);

            $usuario = new Usuario($dados["uid"]->getValue(), $dados["unm"]->getValue());
            $request->user = $usuario;
            return;
        }
        
        SimpleRouter::response()->httpCode(401)->header("WWW-Authenticate: OAuth realm=\"Acesso às funcionalidades da API\"")->json([
            "error" => "Usuário não autenticado",
            "code" => 401
        ], JSON_UNESCAPED_UNICODE);

    }
}