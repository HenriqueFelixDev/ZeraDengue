<?php

namespace App\Controllers;

use App\Models\DB;
use App\Models\Usuario\Endereco;
use App\Models\Usuario\Telefone;
use App\Models\Usuario\Usuario;
use App\Models\Usuario\UsuarioBuilder;
use App\Repositories\IUsuarioRepository;
use App\Repositories\MySQL\UsuarioRepositoryMySQL;
use App\Utils\DadosUtil;
use App\Utils\StringUtil;
use DI\NotFoundException;
use Pecee\SimpleRouter\SimpleRouter;

class UsuarioController
{
    private IUsuarioRepository $usuarioRepository;

    public function __construct()
    {
        $this->usuarioRepository = new UsuarioRepositoryMySQL(DB::obterConexao());
    }

    public function index() : void
    {
        switch (SimpleRouter::request()->getMethod()) {
            case "get":
                $this->obterDados();
                break;
            case "post":
                $this->cadastrar();
                break;

            case "patch":
                $this->atualizar();
                break;
            default:
                throw new NotFoundException("Rota não encontrada", 404);
        }
    }

    public function cadastrar() : void
    {
        $usuario = $this->extrairDadosDoUsuario();
        $resultadoValidacao = $usuario->validarCadastro();

        if ($resultadoValidacao->temErros()) {
            SimpleRouter::response()->httpCode(422)->json($resultadoValidacao->resultadosEmJson(), JSON_UNESCAPED_UNICODE);
        }
        
        $res = $this->usuarioRepository->cadastrar($usuario);
        
        if ($res) {
            return;
        }

        SimpleRouter::response()->httpCode(500)->json([
            "errors" => "Erro ao cadastrar o usuário"
        ], JSON_UNESCAPED_UNICODE);
    }

    public function atualizar() : void
    {
        $usuario = $this->extrairDadosDoUsuario();
        $usuario->id = SimpleRouter::request()->user->id;

        $resultadoValidacao = $usuario->validarAtualizacao();
        
        if ($resultadoValidacao->temErros()) {
            SimpleRouter::response()->httpCode(422)->json($resultadoValidacao->resultadosEmJson(), JSON_UNESCAPED_UNICODE);
        }
        $result = $this->usuarioRepository->atualizar($usuario);

        if ($result) {
            return;
        }

        SimpleRouter::response()->httpCode(500)->json([
            "errors" => "Erro ao atualizar o usuário"
        ], JSON_UNESCAPED_UNICODE);
    }

    public function obterDados()
    {
        $user = SimpleRouter::request()->user;
        $dados = $this->usuarioRepository->obterPorId($user->id);
        if (isset($dados)) {
            unset($dados["senha"]);

            $emailSplit = preg_split("/@/", $dados["email"]);
            $dados["email"] = StringUtil::mascararString($emailSplit[0])."@".$emailSplit[1];
            
            $dados["cpf"] = StringUtil::mascararString($dados["cpf"]);
            $dados["rg"] = StringUtil::mascararString($dados["rg"]);

            SimpleRouter::response()->json($dados, JSON_UNESCAPED_UNICODE);
        }

        SimpleRouter::response()->httpCode(500)->json([
            "errors" => "Erro ao obter os dados do usuário"
        ], JSON_UNESCAPED_UNICODE);
    }

    private function extrairDadosDoUsuario() : Usuario
    {

        $dados = SimpleRouter::request()->getInputHandler();
        $usuarioId = isset(SimpleRouter::request()->user->id) ? SimpleRouter::request()->user->id : 0;

        $nome = filter_var($dados->value("nome"), FILTER_SANITIZE_STRING);
        $email = filter_var($dados->value("email"), FILTER_SANITIZE_STRING);
        $senha = filter_var($dados->value("senha"), FILTER_SANITIZE_STRING);
        $cpf = filter_var($dados->value("cpf"), FILTER_SANITIZE_NUMBER_INT);
        $cpf = intval($cpf);
        $rg = filter_var($dados->value("rg"), FILTER_SANITIZE_STRING);
        $enderecoId = intval($dados->value("endereco_id"));
        $logradouro = filter_var($dados->value("logradouro"), FILTER_SANITIZE_STRING);
        $bairro = filter_var($dados->value("bairro"), FILTER_SANITIZE_STRING);
        $cep = filter_var($dados->value("cep"), FILTER_SANITIZE_STRING);
        $cep = intval($cep);
        $cidade = filter_var($dados->value("cidade"), FILTER_SANITIZE_STRING);
        $estado = filter_var($dados->value("estado"), FILTER_SANITIZE_STRING);
        $numero = filter_var($dados->value("numero"), FILTER_SANITIZE_STRING);
        $complemento = filter_var($dados->value("complemento"), FILTER_SANITIZE_STRING);
        

        $usuarioBuilder = (new UsuarioBuilder())
                ->setUsuario($usuarioId, $nome, $email, $senha,$cpf, $rg)
                ->setEndereco(new Endereco($enderecoId, $usuarioId, $logradouro, $bairro, $cep, $cidade, 
                                $estado, $numero, $complemento));

        $telefones_ids = $dados->value("telefones_ids");
        $ddds = $dados->value("ddds");
        $tels = $dados->value("telefones");
        
        if (isset($tels) && isset($ddds)) {
            for($i = 0; $i < count($tels); $i++) {
                $telefoneId = DadosUtil::getValorArray($telefones_ids, $i);
                $usuarioBuilder->addTelefone(new Telefone($telefoneId, $usuarioId, intval($ddds[$i]), intval($tels[$i])));
            }
        }

        $usuario = $usuarioBuilder->build();
        $usuario->telefonesRemovidos = SimpleRouter::request()->getInputHandler()->value("telefones_removidos");
        
        return $usuario;
    }
    
}