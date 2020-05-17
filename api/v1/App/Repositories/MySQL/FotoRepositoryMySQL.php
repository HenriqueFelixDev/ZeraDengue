<?php

namespace App\Repositories\MySQL;

use App\Repositories\IFotoRepository;
use PDO;
use Exception;

class FotoRepositoryMySQL implements IFotoRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @Override
     */
    public function obterFotosDaDenuncia(int $denunciaId) : array
    {
        $sql = "SELECT foto FROM `Fotos` WHERE denuncia_id = :denunciaId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":denunciaId", $denunciaId);
        $stm->execute();

        if ($stm->rowCount()) {
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * @Override
     */
    public function cadastrar($model) : int
    {
        $sql = "INSERT INTO `Fotos` VALUES (:foto, :denunciaId)";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":foto", $model->foto);
        $stm->bindValue(":denunciaId", $model->denunciaId);
        return $stm->execute() ? 1 : 0;
    }

    /**
     * @Override
     */
    public function obterPorId(int $id)
    {
        throw new Exception("Recurso não implementado");
    }

    /**
     * @Override
     */
    public function atualizar($model) : bool
    {
        throw new Exception("Recurso não implementado");
    }

    /**
     * @Override
     */
    public function deletar(int $id) : bool
    {
        throw new Exception("Recurso não implementado");
    }
}