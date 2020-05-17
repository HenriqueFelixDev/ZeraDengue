<?php

namespace App\Repositories;

use App\Models\Usuario\Usuario;
use App\Repositories\IRepository;

interface IUsuarioRepository extends IRepository{
    public function obterUsuarioPorEmailESenha(String $email, String $senha);
}