<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use App\Models\ImageCache\IImageCache;
use App\Models\ImageCache\SimpleImageCache;

class DenunciaRepository
{
    private Client $client;
    private IImageCache $imageCache;

    public function __construct()
    {
        $this->client = new Client([
            "base_uri" => "http://localhost/projetos/zeradengue/api/v1/",
            "headers" => [
                "Authorization" => "Bearer ".$_SESSION["autenticacao"]->token
            ], 
            "http_errors" => false
        ]);
        $this->imageCache = new SimpleImageCache();
    }
    
    public function consultar(?String $busca = null, ?String $situacao = null, ?String $dataInicial = null, ?String $dataFinal = null, ?int $limite = 15, ?int $paginaAtual = 1)
    {
        $response = $this->client->request("GET", 
            "denuncias?q=$busca&situacao=$situacao&data_inicial=$dataInicial&"
                ."data_final=$dataFinal&limite=$limite&pagina_atual=$paginaAtual");
        return json_decode($response->getBody()->getContents());
    }

    public function buscarPorId(int $id)
    {
        $response = $this->client->request("GET", "denuncias/$id");
        return json_decode($response->getBody()->getContents());
    }

    public function cadastrar($denuncia)
    {
        $dados = [];

        if (isset($_FILES["fotos"]) && $_FILES["fotos"]["size"][0] > 0) {
            $imageIds = $this->imageCache->saveImages($_FILES["fotos"]);
            for($i = 0; $i < count($imageIds); $i++) {
                $dados[] = [
                    "name" => "fotos[$i]",
                    "contents" => $this->imageCache->getImage($imageIds[$i])
                ];
            }
        }

        foreach($denuncia as $chave=>$valor) {
            $dados[] = ["name" => $chave, "contents" => $valor];
        }

        $response = $this->client->request("POST", "denuncias", ["multipart" => $dados]);
        if (isset($imageIds)) {
            $this->imageCache->deleteImages($imageIds);
        }
        
        return json_decode($response->getBody()->getContents());
    }

    public function atualizar($denuncia)
    {
        $denuncia["_method"] = "PATCH";
        $response = $this->client->request("POST", "denuncias", ["form_params" => $denuncia]);
        return $response;
    }
}