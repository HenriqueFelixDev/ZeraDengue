<?php

namespace App\Controllers;

use App\Models\Mensagens\GerenciadorMensagens;
use App\Models\Mensagens\Mensagem;

abstract class Controller
{
    public function salvarMensagensDeErro($result, $campo)
    {
        if (!isset($result->errors)) {
            return;
        }

        if (is_string($result->errors)) {
            GerenciadorMensagens::salvarMensagem(new Mensagem($campo, $result->errors, Mensagem::TIPO_ERRO));
            return;
        }

        $result = (array) $result->errors;
        foreach ($result as $chave=>$valor) {
            GerenciadorMensagens::salvarMensagem(new Mensagem($chave, $valor[0], Mensagem::TIPO_ERRO));
        }
    }
}