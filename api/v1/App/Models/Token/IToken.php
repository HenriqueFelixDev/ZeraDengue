<?php

namespace App\Models\Token;

interface IToken
{
    public function gerarToken($usuario) : ?String;
    public function validarToken(?String $token) : bool;
    public function obterDados(?String $token): ?array;
}