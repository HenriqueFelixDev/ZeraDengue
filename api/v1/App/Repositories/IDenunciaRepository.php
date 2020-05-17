<?php

namespace App\Repositories;

use App\Repositories\IRepository;
use DateTime;

interface IDenunciaRepository extends IRepository
{
    public function obterDenunciasDoUsuario(int $usuarioId, String $pesquisa, String $situacao, DateTime $dataInicial, DateTime $dataFinal, int $limite, int $paginaAtual) : array;
}