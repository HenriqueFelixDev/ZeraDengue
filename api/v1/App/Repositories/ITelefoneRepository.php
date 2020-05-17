<?php

namespace App\Repositories;

use App\Repositories\IRepository;

interface ITelefoneRepository extends IRepository
{
    public function obterTelefonesDoUsuario(int $usuarioId) : array;
}