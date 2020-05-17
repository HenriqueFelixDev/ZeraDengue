<?php

namespace App\Repositories;

use App\Repositories\IRepository;

interface IComentarioRepository extends IRepository
{
    public function obterComentariosDaDenuncia(int $denunciaId) : array;
}