<?php

namespace App\Utils;

interface IGerenciadorImagens
{
    public function salvarImagem(int $usuarioId, $caminhoImagem, String $recurso = "fotos") : ?String;
    public function salvarImagens(int $usuarioId, $imagens, String $recurso = "fotos") : ?array;
    public function deletarImagem(int $usuarioId, String $caminhoImagem) : bool;
}