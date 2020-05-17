<?php

namespace App\Repositories\MySQL;

use App\Repositories\IEnderecoRepository;
use App\Models\Usuario\Endereco;
use PDO;

class EnderecoRepositoryMySQL implements IEnderecoRepository
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @Override
     */
    public function obterEnderecoDoUsuario(int $usuarioId)
    {
        $sql = "SELECT * FROM `Enderecos` WHERE usuario_id = :usuarioId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":usuarioId", $usuarioId);
        $stm->execute();

        if ($stm->rowCount()) {
            return $stm->fetch();
        }
        return null;
    }

    /**
     * @Override
     */
    public function cadastrar($model) : int
    {
        $sql = "INSERT INTO `Enderecos` VALUES(default, :usuarioId, :logradouro, :numero, :bairro, :cep, :cidade, :estado, :complemento)";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":usuarioId", $model->usuarioId);
        $stm->bindValue(":logradouro", $model->logradouro);
        $stm->bindValue(":numero", $model->numero);
        $stm->bindValue(":bairro", $model->bairro);
        $stm->bindValue(":cep", $model->cep);
        $stm->bindValue(":cidade", $model->cidade);
        $stm->bindValue(":estado", $model->estado);
        $stm->bindValue(":complemento", $model->complemento);
        
        return $stm->execute() ? 1 : 0;
    }

    /**
     * @Override
     */
    public function obterPorId($id)
    {
        $sql = "SELECT * FROM `Enderecos` WHERE endereco_id = :enderecoId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":enderecoId", $id);
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
        $sql  = "UPDATE `Enderecos` SET ";
        $sql .= "logradouro = :logradouro, bairro = :bairro, cep = :cep, cidade = :cidade, ";
        $sql .= "estado = :estado, numero = :numero, complemento = :complemento ";
        $sql .= "WHERE endereco_id = :enderecoId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":logradouro", $model->logradouro);
        $stm->bindValue(":bairro", $model->bairro);
        $stm->bindValue(":cep", $model->cep);
        $stm->bindValue(":cidade", $model->cidade);
        $stm->bindValue(":estado", $model->estado);
        $stm->bindValue(":numero", $model->numero);
        $stm->bindValue(":complemento", $model->complemento);
        $stm->bindValue(":enderecoId", $model->id, PDO::PARAM_INT);

        return $stm->execute();
    }

    /**
     * @Override
     */
    public function deletar($id) : bool
    {
        $sql = "DELETE FROM `Enderecos` WHERE endereco_id = :enderecoId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":enderecoId", $id);
        
        return $stm->execute();
    }
}