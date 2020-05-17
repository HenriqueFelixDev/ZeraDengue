<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use Pecee\SimpleRouter\SimpleRouter;

class ComentarioRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            "base_uri" => "http://localhost/projetos/zeradengue/api/v1/",
            "headers" => [
                "Authorization" => "Bearer ".$_SESSION["autenticacao"]->token
            ],
            "http_errors" => false
        ]);
    }
    public function cadastrar(int $denunciaId, String $comentario)
    {
        $comentarioDados = ["comentario" => $comentario];
        $response = $this->client->request("POST", "denuncias/$denunciaId/comentarios", ["form_params" => $comentarioDados]);
        return json_decode($response->getBody()->getContents());
    }

    public function atualizar(int $denunciaId, int $comentarioId, String $comentario)
    {
        $comentario = ["_method" => "PATCH", "comentario" => $comentario];
        $response = $this->client->request("POST", "denuncias/$denunciaId/comentarios/$comentarioId", ["form_params" => $comentario]);
        return json_decode($response->getBody()->getContents());
    }

    public function obterPorDenunciaId(int $denunciaId)
    {
        $response = $this->client->request("GET", "denuncias/$denunciaId/comentarios");
        return json_decode($response->getBody()->getContents());
    }

    public function obterPorId(int $denunciaId, int $comentarioId)
    {
        $response = $this->client->request("GET", "denuncias/$denunciaId/comentarios/$comentarioId");
        return json_decode($response->getBody()->getContents());
    }

    public function deletar(int $denunciaId, int $comentarioId)
    {
        $response = $this->client->request("DELETE", "denuncias/$denunciaId/comentarios/$comentarioId");
        return json_decode($response->getBody()->getContents());
    }
}