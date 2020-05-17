<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::setDefaultNamespace("\App\Controllers");

SimpleRouter::group(["prefix" => "/projetos/zeradengue/portal"], function(){

    SimpleRouter::get("/cadastro", "UsuarioController@cadastro")->name("usuario.cadastro");
    SimpleRouter::post("/cadastrar", "UsuarioController@cadastrarOuAtualizar")->name("usuario.cadastrar");

    SimpleRouter::post("/entrar", "AutenticacaoController@logar", [
        "parameters" => [
            "email" => SimpleRouter::request()->getInputHandler()->value("email"),
            "senha" => SimpleRouter::request()->getInputHandler()->value("senha")
    ]]);
    
    SimpleRouter::group(["middleware" => \App\Middlewares\AutenticacaoMiddleware::class], function(){
        SimpleRouter::get("/login", "AutenticacaoController@login")->name("usuario.login");
        SimpleRouter::get("/deslogar", "AutenticacaoController@deslogar");

        SimpleRouter::get("/", "HomeController@index")->name("home");
        SimpleRouter::get("/conta", "UsuarioController@conta")->name("usuario.conta");
        SimpleRouter::post("/atualizar", "UsuarioController@cadastrarOuAtualizar")->name("usuario.atualizar");

        SimpleRouter::group(["prefix" => "/denuncias"], function(){
            SimpleRouter::get("/", "DenunciaController@index")->name("denuncias");

            SimpleRouter::get("/nova", "DenunciaController@novaDenuncia")->name("denuncias.nova");
            SimpleRouter::post("/salvar", "DenunciaController@cadastrarOuAtualizarDenuncia")->name("denuncias.salvar");
        
            SimpleRouter::get("/{denuncia_id}", "DenunciaController@visualizacaoDenuncia")
                    ->where(["denuncia_id" => "[0-9]+"])
                    ->name("denuncias.visualizar");

            SimpleRouter::post("/{denuncia_id}/comentarios", "ComentarioController@cadastrarOuAtualizarComentario");

            SimpleRouter::patch("/{denuncia_id}/comentarios/{comentario_id}", "ComentarioController@cadastrarOuAtualizarComentario");
            SimpleRouter::get("/{denuncia_id}/comentarios/{comentario_id}/excluir", "ComentarioController@deletarComentario");
        });
    });
});