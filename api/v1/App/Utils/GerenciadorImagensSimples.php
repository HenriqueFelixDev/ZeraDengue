<?php

namespace App\Utils;

class GerenciadorImagensSimples implements IGerenciadorImagens
{
    private const CAMINHO_BASE = __DIR__."/../Resources/Images/";
    public function salvarImagem(int $usuarioId, $caminhoImagem, String $recurso = "fotos") : ?String
    {   
        $novoNome = uniqid().".jpg";

        $caminhoRelativo = "$recurso/$usuarioId/";
        $caminho = self::CAMINHO_BASE.$caminhoRelativo;
        $caminhoCompleto = $caminho.$novoNome;

        if(!file_exists($caminho)) {
            mkdir($caminho, 0777, true);
        }

        $res = @move_uploaded_file($caminhoImagem, $caminhoCompleto);

        if($res) {
            return $caminhoRelativo.$novoNome;
        }
        return null;
    }

    public function salvarImagens(int $usuarioId, $imagens, String $recurso = "fotos") : ?array
    {
        if (!isset($imagens) || !isset($usuarioId)) {
            return null;
        }

        $caminhosImagens = [];
        
        for($i = 0; $i < count($imagens["tmp_name"]); $i++) {
            if (is_bool(strpos($imagens["type"][$i], "image"))) {
                return null;
            }

            $caminhoImagem = $this->salvarImagem($usuarioId, $imagens["tmp_name"][$i]);
            
            if (!isset($caminhoImagem)) {
                foreach($caminhosImagens as $caminhoSalvo) {
                    $this->deletarImagem($usuarioId, $caminhoSalvo);
                }
                return null;
            }
            $caminhosImagens[] = $caminhoImagem;
        }
        return $caminhosImagens;
    }

    public function deletarImagem(int $usuarioId, String $caminhoImagem) : bool
    {
        $caminhoCompleto = self::CAMINHO_BASE.$caminhoImagem;
        if (!file_exists($caminhoCompleto) || !preg_split("/", $caminhoImagem)[1] == $usuarioId) {
            return false;
        }
        return unlink($caminhoCompleto);
    }
}