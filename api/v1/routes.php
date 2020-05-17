<?php

use Pecee\SimpleRouter\SimpleRouter;
use App\Utils\DadosUtil;

$url = "/projetos/zeradengue/api/v1";

SimpleRouter::setDefaultNamespace("\App\Controllers");

SimpleRouter::group(["prefix" => $url], function() {

    // Autenticação
    SimpleRouter::get("/autenticar", "AutenticacaoController@autenticar", 
        ["parameters" => [
            "email" => SimpleRouter::request()->getInputHandler()->value("email"),
            "senha" => SimpleRouter::request()->getInputHandler()->value("senha")
        ]]);

    SimpleRouter::get("/validar-token", "AutenticacaoController@validarToken", 
        ["parameters" => [
            "token" => DadosUtil::getValorArray(apache_request_headers(), "Authorization")
        ]]);
    
    SimpleRouter::group(["middleware" => \App\Middlewares\AutenticacaoMiddleware::class], function() {

        // Usuários
        SimpleRouter::group(["prefix" => "/usuarios"], function() {
            SimpleRouter::match(["get", "post", "patch"], "/", "UsuarioController@index");
        });

        // Denúncias
        SimpleRouter::group(["prefix" => "/denuncias"], function() {
            SimpleRouter::form("/", "DenunciaController@index");
            
            SimpleRouter::get("/{denuncia_id}", "DenunciaController@obterDenunciaPorId");
            
            SimpleRouter::form("/{denuncia_id}/comentarios", "ComentarioController@index");
            
            SimpleRouter::match(["get", "patch", "delete"], "/{denuncia_id}/comentarios/{comentario_id}", "ComentarioController@index");
        });

    });

});