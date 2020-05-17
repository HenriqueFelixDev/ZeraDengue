<?php

namespace App\Models\Denuncia;

use JsonSerializable;

class DenunciaInfo implements JsonSerializable
{
    public int $totalItens;
    public int $itensPorPagina;
    public ?String $paginaAnterior;
    public ?String $proximaPagina;
    public String $self;
    public array $itens;

    /**
     * @Override
     */
    public function jsonSerialize()
    {
        return [
            "totalItens" => $this->totalItens,
            "itensPorPagina" => $this->itensPorPagina,
            "paginaAnterior" => $this->paginaAnterior,
            "proximaPagina" => $this->proximaPagina,
            "self" => $this->self,
            "itens" => $this->itens
        ];
    }
}