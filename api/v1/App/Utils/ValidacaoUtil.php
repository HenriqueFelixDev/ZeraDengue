<?php

namespace App\Utils;

use Exception;
use DateTime;

class ValidacaoUtil
{
    public const DATA_PASSADA = -1;
    public const DATA_FUTURA = 1;
    public const DATA_QUALQUER = 0;

    public static function aoErro(bool $resultado, String $mensagemErro) : ?String
    {
        return $resultado ? null : $mensagemErro;
    }

    public static function naoVazio($parametro) : bool
    {
        return !empty($parametro);
    }

    public static function tamanhoEntre($parametro, int $min, int $max) : bool
    {
        return self::tamanhoMin($parametro, $min) && self::tamanhoMax($parametro, $max);
    }

    public static function tamanhoMin($parametro, int $minimo) : bool
    {
        if(!gettype($parametro) == 'string') {
            throw new Exception('O parâmetro para este método deve ser uma string');
        }

        return strlen($parametro) >= $minimo;
    }

    public static function tamanhoMax($parametro, int $maximo) : bool
    {
        if(!gettype($parametro) == 'string') {
            throw new Exception('O parâmetro para este método deve ser uma string');
        }

        return strlen($parametro) <= $maximo;
    }

    public static function valorEntre($parametro, $min, $max) : bool
    {
        if(!gettype($parametro) == 'int' || !gettype($parametro) == 'float') {
            throw new Exception('O parâmetro para este método deve ser um número');
        }

        return $parametro >= $min && $parametro <= $max;
    }

    public static function valorMin($parametro, $minimo) : bool
    {
        if(!gettype($parametro) == 'int' || !gettype($parametro) == 'float') {
            throw new Exception('O parâmetro para este método deve ser um número');
        }

        return $parametro >= $minimo;
    }

    public static function valorMax($parametro, $maximo) : bool
    {
        if(!gettype($parametro) == 'int' || !gettype($parametro) == 'float') {
            throw new Exception('O parâmetro para este método deve ser um número');
        }

        return $parametro >= $maximo;
    }

    public static function somenteNumeros($parametro) : bool
    {
        return preg_match("/\d+/", $parametro);
    }

    public static function somenteLetras($parametro) : bool
    {
        return preg_match("/\w+/", $parametro);
    }

    public static function telefone($valor, bool $noveDigitos = true) : bool
    {
        $padrao = $noveDigitos ? "/\(?\d{2}\)?9\d{4}-?\d{4}/" : "/\(?\d{2}\)?\d{4}-?\d{4}/";

        return preg_match($padrao, $valor);
    }

    public static function email($valor) : bool
    {
        return preg_match("/.{2,}@\w{2,}\.\w{2,}/", $valor);
    }

    public static function cpf($valor) : bool
    {
        return preg_match("/\d{3}\.?\d{3}\.?\d{3}-?\d{2}/", $valor);
    }

    public static function cep($valor) : bool
    {
        return preg_match("/\d{2}\.?\d{3}-?\d{3}/", $valor);
    }

    public static function data($valor, int $tipoData = self::DATA_QUALQUER) : bool
    {
        switch($tipoData) {
            case self::DATA_FUTURA:
                return self::dataFutura($valor);
                break;

            case self::DATA_PASSADA:
                return self::dataPassada($valor);
                break;

            case self::DATA_QUALQUER:
                return self::dataQualquer($valor);
                break;

            default:
                throw new Exception('Tipo da data inválido');
        }
    }

    private static function dataFutura($valor) : bool
    {
        if (self::dataQualquer($valor)) {
            $dif = (new DateTime())->diff(new DateTime($valor));
            return $dif->invert;
        }
        return false;
    }

    private static function dataPassada($valor) : bool
    {
        if (self::dataQualquer($valor)) {
            $dif = (new DateTime())->diff(new DateTime($valor));
            return !$dif->invert;
        }
        return false;
    }

    private static function dataQualquer($valor) : bool
    {
        try {
            new DateTime($valor);
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
}