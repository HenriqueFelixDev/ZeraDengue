<?php

namespace App\Repositories\MySQL;

use App\Models\Denuncia\Foto;
use App\Repositories\IDenunciaRepository;
use App\Repositories\IFotoRepository;
use PDO;
use DateTime;
use Exception;
use Pecee\SimpleRouter\SimpleRouter;

class DenunciaRepositoryMySQL implements IDenunciaRepository
{
    private PDO $db;
    private IFotoRepository $fotoRepository;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->fotoRepository = new FotoRepositoryMySQL($db);
    }

    /**
     * @Override
     */
    public function obterDenunciasDoUsuario(int $usuarioId, ?String $pesquisa, ?String $situacao = "qualquer", ?DateTime $dataInicial, ?DateTime $dataFinal, int $limite, int $paginaAtual) : array
    {
        $totalItens = 0;
        $result = [];

        $sql  = "SELECT * FROM `Denuncias` WHERE usuario_id = :usuarioId";
        
        if (!empty($pesquisa)) {
            $sql .= " AND (assunto LIKE :pesquisa OR descricao LIKE :pesquisa)";
        }

        if(!empty($situacao)) {
            if($situacao !== "qualquer") {
                $sql .= " AND situacao = :situacao";
            }
        }

        if (!empty($dataInicial)) {
            $sql .= " AND data_publicacao >= :dataInicial";
        }

        if (!empty($dataFinal)) {
            $sql .= " AND data_publicacao <= :dataFinal";
        }

        $paginaAtual--;

        $sqlContador = str_replace("*", "count(*)", $sql);
        $sql .= " LIMIT :pagina,:limite";

        $stmContador = $this->db->prepare($sqlContador);
        $stm = $this->db->prepare($sql);
        $stm->bindValue(":usuarioId", $usuarioId, PDO::PARAM_INT);
        $stmContador->bindValue(":usuarioId", $usuarioId, PDO::PARAM_INT);

        if (!empty($pesquisa)) {
            $stm->bindValue(":pesquisa", "%$pesquisa%");
            $stmContador->bindValue(":pesquisa", "%$pesquisa%");
        }

        if(!empty($situacao)) {
            if($situacao !== "qualquer") {
                $stm->bindValue(":situacao", $situacao);
                $stmContador->bindValue(":situacao", $situacao);
            }
        }

        if (!empty($dataInicial)) {
            $stm->bindValue(":dataInicial", $dataInicial->format('Y-m-d H:i:s'));
            $stmContador->bindValue(":dataInicial", $dataInicial->format('Y-m-d H:i:s'));
        }

        if (!empty($dataFinal)) {
            $stm->bindValue(":dataFinal", $dataFinal->format('Y-m-d H:i:s'));
            $stmContador->bindValue(":dataFinal", $dataFinal->format('Y-m-d H:i:s'));
        }

        

        $stmContador->execute();
        

        if ($stmContador->rowCount()) {
            $totalItens = $stmContador->fetch(PDO::FETCH_COLUMN);

            $stm->bindValue(":pagina", $paginaAtual * $limite, PDO::PARAM_INT);
            $stm->bindValue(":limite", $limite, PDO::PARAM_INT);
            $stm->execute();

            if($stm->rowCount()) {
                $result = $stm->fetchAll();
            }
        }

        return ["total" => $totalItens, "resultados" => $result];
    }

    /**
     * @Override
     */
    public function cadastrar($model) : int
    {
        try {
            $sql = "INSERT INTO `Denuncias` VALUES(default, :usuarioId, :assunto, :descricao, :latitudeFoco, :longitudeFoco, :localFoco, :dataPublicacao, default)";

            $this->db->beginTransaction();
            
            $stm = $this->db->prepare($sql);
            $stm->bindValue(":usuarioId", $model->usuarioId);
            $stm->bindValue(":assunto", $model->assunto);
            $stm->bindValue(":descricao", $model->descricao);
            $stm->bindValue(":latitudeFoco", $model->coordenadasFoco->latitude);
            $stm->bindValue(":longitudeFoco", $model->coordenadasFoco->longitude);
            $stm->bindValue(":localFoco", $model->localFoco);
            $stm->bindValue(":dataPublicacao", $model->dataPublicacao->format("Y-m-d H:i:s"));
            
            if (!$stm->execute()) {
                throw new Exception("Erro ao salvar a foto");
            }
            $id = $this->db->lastInsertId();

            foreach($model->fotos as $foto) {
                $ft = new Foto();
                $ft->denunciaId = $id;
                $ft->foto = $foto;

                $result = $this->fotoRepository->cadastrar($ft);

                if (!$result) {
                    throw new Exception("Erro ao salvar a foto");
                }
            }

            return $this->db->commit() ? 1 : 0;
        } catch (Exception $e) {
            $this->db->rollBack();
        }
        return 0;
    }

    /**
     * @Override
     */
    public function obterPorId(int $id)
    {
        $sql = "SELECT * FROM `Denuncias` WHERE denuncia_id = :denunciaId AND usuario_id = :usuarioId";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":denunciaId", $id);
        $stm->bindValue(":usuarioId", SimpleRouter::request()->user->id);
        $stm->execute();

        if ($stm->rowCount()) {
            $denuncia = $stm->fetch();

            $fotos = $this->fotoRepository->obterFotosDaDenuncia($id);
            
            if(count($fotos) > 0) {
                $denuncia->fotos = $fotos;
            }

            return $denuncia;
        }
        return null;
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
    public function deletar($id) : bool
    {
        throw new Exception("Recurso não implementado");
    }
}