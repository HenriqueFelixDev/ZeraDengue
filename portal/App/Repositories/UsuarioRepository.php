<?php

namespace App\Repositories;

use GuzzleHttp\Client;

class UsuarioRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            "base_uri" => "http://localhost/projetos/zeradengue/api/v1/",
            "http_errors" => false
        ]);
    }

    public function cadastrar($usuario)
    {
        return $this->cadastrarOuAtualizar($usuario);
    }

    public function atualizar($usuario)
    {
        $usuario["_method"] = "PATCH";
        return $this->cadastrarOuAtualizar($usuario);
    }

    public function obterDados()
    {
        $result = $this->client->request("GET", "usuarios", [
            "headers" => [
                "Authorization" => "Bearer ".$_SESSION["autenticacao"]->token
            ]
        ]);
        return json_decode($result->getBody()->getContents());
    }

    private function cadastrarOuAtualizar($usuario)
    {
        $headers = [];
        if(isset($usuario["_method"]) && $usuario["_method"] == "PATCH") {
            $headers = ["Authorization" => "Bearer ".$_SESSION["autenticacao"]->token];
        }

        $response = $this->client->request("POST", "usuarios", [
            "form_params" => $usuario, 
            "headers" => $headers
        ]);
        return json_decode($response->getBody()->getContents());
    }
}