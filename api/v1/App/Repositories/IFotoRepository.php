<?php

namespace App\Repositories;

use App\Repositories\IRepository;

interface IFotoRepository extends IRepository {
    public function obterFotosDaDenuncia(int $denunciaId) : array;
}