<?php

namespace App\Repositories;

use App\Models\Usuario\Endereco;
use App\Repositories\IRepository;

interface IEnderecoRepository extends IRepository
{
    public function obterEnderecoDoUsuario(int $usuarioId);
}