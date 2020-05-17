<?php

namespace App\Repositories\MySQL;

use App\Repositories\IComentarioRepository;
use PDO;

class ComentarioRepositoryMySQL implements IComentarioRepository
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @Override
     */
    public function obterComentariosDaDenuncia(int $denunciaId) : array
    {
        $sql = "SELECT c.comentario_id, c.usuario_id, c.denuncia_id, c.responde_a, c.comentario, c.data_publicacao, c.data_atualizacao, u.nome as \"autor\", u.tipo_usuario FROM `Comentarios` c INNER JOIN `Usuarios` u ON u.usuario_id = c.usuario_id WHERE denuncia_id = :denunciaId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":denunciaId", $denunciaId);
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
        $sql = "INSERT INTO `Comentarios` VALUES(default, :usuarioId, :denunciaId, :respondeA, :comentario, :dataPublicacao, :dataAtualizacao)";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":usuarioId", $model->usuarioId, PDO::PARAM_INT);
        $stm->bindValue(":denunciaId", $model->denunciaId, PDO::PARAM_INT);
        $stm->bindValue(":respondeA", $model->respondeA, PDO::PARAM_INT);
        $stm->bindValue(":comentario", $model->comentario);
        $stm->bindValue(":dataPublicacao", $model->dataPublicacao->format("Y-m-d H:i:s"));
        $stm->bindValue(":dataAtualizacao", null);

        return $stm->execute() ? 1 : 0;
    }

    /**
     * @Override
     */
    public function obterPorId($id)
    {
        $sql = "SELECT c.comentario_id, c.usuario_id, c.denuncia_id, c.responde_a, c.comentario, c.data_publicacao, c.data_atualizacao, u.nome, u.tipo_usuario FROM `Comentarios` c INNER JOIN `Usuarios` u ON u.usuario_id = c.usuario_id  WHERE comentario_id = :comentarioId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":comentarioId", $id);
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
        $sql = "UPDATE `Comentarios` SET comentario = :comentario, data_atualizacao = :dataAtualizacao WHERE comentario_id = :comentarioId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":comentario", $model->comentario);
        $stm->bindValue(":dataAtualizacao", $model->dataAtualizacao->format("Y-m-d H:i:s"));
        $stm->bindValue(":comentarioId", $model->id, PDO::PARAM_INT);

        return $stm->execute();
    }

    /**
     * @Override
     */
    public function deletar($id) : bool
    {
        $sql = "DELETE FROM `Comentarios` WHERE comentario_id = :comentarioId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":comentarioId", $id, PDO::PARAM_INT);
        
        return $stm->execute();
    }
}