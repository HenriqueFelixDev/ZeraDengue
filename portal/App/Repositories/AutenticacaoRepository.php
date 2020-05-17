<?php

namespace App\Repositories;

use GuzzleHttp\Client;

class AutenticacaoRepository
{
    private Client $client;
    public function __construct()
    {
        $this->client = new Client([
            "base_uri" => "http://localhost/projetos/zeradengue/api/v1/",
            "http_errors" => false
        ]);
    }
    public function autenticar(String $email, String $senha)
    {
        $response = $this->client->request('GET', "autenticar?email=$email&senha=$senha");
        return json_decode($response->getBody()->getContents());
    }
    
    public function validarAutenticacao()
    {   
        $response = $this->client->request('GET', "validar-token", ["headers" => [
            "Authorization" => "Bearer ".$_SESSION["autenticacao"]->token
        ]]);
        return json_decode($response->getBody()->getContents());
    }
}