<?php

namespace App\Repositories;

use PDO;

interface IRepository
{
    public function cadastrar($model) : int;
    public function obterPorId(int $id);
    public function atualizar($model) : bool;
    public function deletar(int $id) : bool;
}