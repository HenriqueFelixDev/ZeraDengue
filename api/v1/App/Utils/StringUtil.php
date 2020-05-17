<?php

namespace App\Utils;

use InvalidArgumentException;

class StringUtil
{
    public static function mascararString(String $valor, String $mascara = "*") : ?String
    {
        if (empty($valor) || empty($mascara)) {
            throw new InvalidArgumentException("Os atributos desse método não podem ser vazios ou nulos");
        }
        
        $tamanhoMascara = strlen($valor) - 2;
        $mascara = str_pad("", $tamanhoMascara, $mascara);
        return substr_replace($valor, $mascara, 1, $tamanhoMascara);
    }
}