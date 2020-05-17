<?php

namespace App\Models\Usuario;

class UsuarioBuilder
{
    private Usuario $usuario;

    public function setUsuario(?int $id, ?String $nome, ?String $email, ?String $senha, ?int $cpf, ?String $rg, ?String $tipoUsuario = Usuario::COMUM) : self
    {
        
        $this->usuario = new Usuario($id, $nome, $email, $senha, $cpf, $rg, $tipoUsuario);
        return $this;
    }

    public function setEndereco(Endereco $endereco) : self
    {
        $this->usuario->endereco = $endereco;
        return $this;
    }

    public function addTelefone(Telefone $telefone) : self
    {
        $this->usuario->telefones[] = $telefone;
        return $this;
    }

    public function build()
    {
        return $this->usuario;
    }
}