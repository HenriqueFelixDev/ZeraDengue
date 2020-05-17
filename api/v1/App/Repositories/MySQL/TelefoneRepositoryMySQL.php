<?php

namespace App\Repositories\MySQL;

use App\Repositories\ITelefoneRepository;
use PDO;

class TelefoneRepositoryMySQL implements ITelefoneRepository
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @Override
     */
    public function obterTelefonesDoUsuario(int $usuarioId) : array
    {
        $sql = "SELECT * FROM `Telefones` WHERE usuario_id = :usuarioId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":usuarioId", $usuarioId);
        $stm->execute();

        if ($stm->rowCount()) {
            return $stm->fetchAll();
        }

        return [];
    }

    /**
     * @Override
     */
    public function cadastrar($model) : int
    {
        $sql = "INSERT INTO `Telefones` VALUES(default, :usuarioId, :ddd, :telefone)";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":usuarioId", $model->usuarioId);
        $stm->bindValue(":ddd", $model->ddd);
        $stm->bindValue(":telefone", $model->telefone);
        
        return $stm->execute() ? 1 : 0;
    }

    /**
     * @Override
     */
    public function obterPorId($id)
    {
        $sql = "SELECT * FROM `Telefones` WHERE telefone_id = :telefoneId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":telefoneId", $id);
        $stm->execute();

        if ($stm->rowCount()) {
            return $stm->fetch();
        }
        return null;
    }

    /**
     * @Override
     */
    public function atualizar($model) : bool
    {
        $sql = "UPDATE `Telefones` SET ddd = :ddd, telefone = :telefone WHERE telefone_id = :telefoneId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":ddd", $model->ddd);
        $stm->bindValue(":telefone", $model->telefone);
        $stm->bindValue(":telefoneId", $model->id);
        
        return $stm->execute();
    }

    /**
     * @Override
     */
    public function deletar($id) : bool
    {
        $sql = "DELETE FROM `Telefones` WHERE telefone_id = :telefoneId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":telefoneId", $id);
        
        return $stm->execute();
    }
}