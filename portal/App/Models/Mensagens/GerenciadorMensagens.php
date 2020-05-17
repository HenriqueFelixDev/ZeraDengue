<?php

namespace App\Models\Mensagens;

use App\Models\Mensagens\Mensagem;

class GerenciadorMensagens
{
    public static function salvarMensagem(Mensagem $mensagem)
    {
        if(!isset($mensagem) || empty($mensagem->campo) || empty($mensagem->mensagem)) {
            return;
        }

        $_SESSION["mensagens"][$mensagem->campo] = $mensagem;
    }

    public static function obterMensagem(String $campo)
    {
        if (!array_key_exists("mensagens", $_SESSION) || !array_key_exists($campo, $_SESSION["mensagens"])) {
            return null;
        }
        
        $mensagem =  $_SESSION["mensagens"][$campo];
        return isset($mensagem) ? $mensagem : null;
    }

    public static function obterMensagens()
    {
        if (!array_key_exists("mensagens", $_SESSION)) {
            return null;
        }

        $mensagens = (object) $_SESSION["mensagens"];
        return isset($mensagens) ? $mensagens : null;
    }

    public static function deletarMensagens()
    {
        unset($_SESSION["mensagens"]);
    }
}