<?php

namespace App\Controllers;

use App\Models\DB;
use App\Models\Token\JWTToken;
use App\Models\Usuario\Usuario;
use App\Repositories\IUsuarioRepository;
use App\Repositories\MySQL\UsuarioRepositoryMySQL;
use Pecee\SimpleRouter\SimpleRouter;

class AutenticacaoController
{
    private IUsuarioRepository $usuarioRepository;

    public function __construct()
    {
        $this->usuarioRepository = new UsuarioRepositoryMySQL(DB::obterConexao());
    }

    public function autenticar(String $email = null, String $senha = null)
    {
        $usuario = new Usuario(null, null, $email, $senha);

        $resultadoValidacao = $usuario->validarLogin();
        if ($resultadoValidacao->temErros()) {
            SimpleRouter::response()
                ->httpCode(422)
                ->json($resultadoValidacao->resultadosEmJson(), JSON_UNESCAPED_UNICODE);
        }

        $usuario = $this->usuarioRepository->obterUsuarioPorEmailESenha($email, $senha);

        if (isset($usuario)) {
            $token = (new JWTToken())->gerarToken($usuario);
            $dados = [
                "id" => $usuario->usuario_id,
                "nome" => $usuario->nome,
                "token" => $token
            ];
    
            SimpleRouter::response()->json($dados, JSON_UNESCAPED_UNICODE);
        }

        SimpleRouter::response()
            ->httpCode(404)
            ->json(["errors" => "Usuário não encontrado"], JSON_UNESCAPED_UNICODE);
    }

    public function validarToken(String $token = null)
    {
        SimpleRouter::response()->json([
            "result" => (new JWTToken())->validarToken(str_replace("Bearer ", "", $token))
        ], JSON_UNESCAPED_UNICODE);
    }

}