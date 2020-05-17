<?php

namespace App\Repositories\MySQL;

use App\Repositories\IEnderecoRepository;
use App\Repositories\ITelefoneRepository;
use App\Repositories\IUsuarioRepository;
use Exception;
use PDO;

class UsuarioRepositoryMySQL implements IUsuarioRepository
{
    private PDO $db;
    private ITelefoneRepository $telefoneRepository;
    private IEnderecoRepository $enderecoRepository;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->telefoneRepository = new TelefoneRepositoryMySQL($db);
        $this->enderecoRepository = new EnderecoRepositoryMySQL($db);
    }

    /**
     * @Override
     */
    public function obterUsuarioPorEmailESenha(String $email, String $senha)
    {
        $sql = "SELECT * FROM `Usuarios` WHERE email = :email";
        
        $stm = $this->db->prepare($sql);
        $stm->bindValue(":email", $email);
        $stm->execute();

        if ($stm->rowCount()) {
            foreach($stm->fetchAll() as $usuario) {
                if(password_verify($senha, $usuario->senha)) {
                    return $usuario;
                }
            }
        }
        return null;
    }
    
    /**
     * @Override
     */
    public function cadastrar($model) : int
    {
        try {
            $this->db->beginTransaction();
            

            $id = $this->cadastrarUsuario($model);
            if ($id == -1) {
                throw new Exception("Erro ao cadastrar o usuário");
            }

            foreach($model->telefones as $tel) {
                $tel->usuarioId = $id;
                $telId = $this->telefoneRepository->cadastrar($tel);
                if ($telId == -1) {
                    throw new Exception("Erro ao cadastrar os telefones");
                }
            }
            
            $model->endereco->usuarioId = $id;
            $enderecoId = $this->enderecoRepository->cadastrar($model->endereco);
            
            if ($enderecoId == -1) {
                throw new Exception("Erro ao cadastrar o endereço");
            }

            $this->db->commit();
            return 1;
        } catch(\Exception $e) {
            $this->db->rollBack();
        }
        return 0;
    }

    /**
     * @Override
     */
    public function obterPorId($id)
    {
        $sql = "SELECT * FROM `Usuarios` WHERE usuario_id = :usuarioId";

        $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $stm = $this->db->prepare($sql);
        $stm->bindValue(":usuarioId", $id);
        $stm->execute();

        if($stm->rowCount()) {
            $usuario = $stm->fetch();

            $endereco = $this->enderecoRepository->obterEnderecoDoUsuario($id);
            $telefones = $this->telefoneRepository->obterTelefonesDoUsuario($id);

            $usuario["endereco"] = $endereco;
            $usuario["telefones"] = $telefones;
            return $usuario;
        }
        return null;
    }

    /**
     * @Override
     */
    public function atualizar($model) : bool
    {
        try {
            $sql  = "UPDATE `Usuarios` SET nome = :nome, tipo_usuario = :tipoUsuario WHERE usuario_id = :usuarioId";

            $this->db->beginTransaction();
            $stm = $this->db->prepare($sql);
            $stm->bindValue(":nome" , $model->nome);
            $stm->bindValue(":tipoUsuario", $model->tipoUsuario);
            $stm->bindValue(":usuarioId", $model->id);
            $stm->execute();

            $model->endereco->usuarioId = $model->id;
            $resultEndereco = $this->enderecoRepository->atualizar($model->endereco);

            if(!$resultEndereco) {
                throw new Exception("Erro ao atualizar o endereço");
            }

            foreach($model->telefones as $telefone) {
                $telefone->usuarioId = $model->id;

                if (isset($telefone->id)) {
                    $resultTelefone = $this->telefoneRepository->atualizar($telefone);
                } else {
                    $resultTelefone = $this->telefoneRepository->cadastrar($telefone);
                }

                if(!$resultTelefone) {
                    throw new Exception("Erro ao atualizar o telefone");
                }
            }

            if (isset($model->telefonesRemovidos)) {
                foreach($model->telefonesRemovidos as $telefoneRemovido) {
                    $resultTelefone = $this->telefoneRepository->deletar($telefoneRemovido);
                }

                if(!$resultTelefone) {
                    throw new Exception("Erro ao deletar o telefone o telefone");
                }
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
        }
        return false;
    }

    /**
     * @Override
     */
    public function deletar($id) : bool
    {
        throw new Exception("Recurso não implementado");
    }

    private function cadastrarUsuario($model) : int
    {
        $sql  = "INSERT INTO `Usuarios` VALUES(default, :nome, :email, :senha, :cpf, :rg, :tipoUsuario)";

        $stm = $this->db->prepare($sql);
        $stm->bindValue(":nome" , $model->nome);
        $stm->bindValue(":email", $model->email);
        $stm->bindValue(":senha", password_hash($model->senha, PASSWORD_DEFAULT));
        $stm->bindValue(":cpf"  , $model->cpf);
        $stm->bindValue(":rg"   , $model->rg);
        $stm->bindValue(":tipoUsuario", $model->tipoUsuario);
        
        try {
            if ($stm->execute()) {
                return $this->db->lastInsertId();
            }
        } catch(\Exception $e) {}
        return -1;
    }
}