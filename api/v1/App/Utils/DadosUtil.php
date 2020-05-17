<?php

namespace App\Utils;

class DadosUtil 
{
    public static function getValor($valor, $default = null) 
    {
        return isset($valor) ? $valor : $default;
    }

    public static function getValorArray($array, $indice, $default = null) {
        if(!isset($array) || !isset($indice)) {
            return $default;
        }

        return array_key_exists($indice, $array) 
            ? self::getValor($array[$indice], $default)
            : $default;
    }
}