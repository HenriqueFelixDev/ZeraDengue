<?php

namespace App\Models\Usuario;

use App\Models\Validacoes\ResultadoValidacao;
use App\Models\Validacoes\Validacao;
use App\Utils\ValidacaoUtil;

class Usuario
{
    public const COMUM = 'comum';
    public const MINISTERIO_SAUDE = 'ministerio_saude';

    public ?int $id;
    public ?String $nome;
    public ?String $email;
    public ?String $senha;
    public ?int $cpf;
    public ?String $rg;
    public ?Endereco $endereco;
    public ?array $telefones;
    public ?String $tipoUsuario;
    public ?array $telefonesRemovidos;

    public function __construct(?int $id = null, ?String $nome = null, ?String $email = null, ?String $senha = null, ?int $cpf = null, ?String $rg = null, ?String $tipoUsuario = Usuario::COMUM)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->cpf = $cpf;
        $this->rg = $rg;
        $this->tipoUsuario = $tipoUsuario;
    }

    public function __set($prop, $valor) {
        switch ($prop) {
            case "usuario_id":
                $this->id = $valor;
            case "tipo_usuario":
                $this->tipoUsuario = $valor;
        }
    }

    public function validarLogin() : ResultadoValidacao
    {
        $resultadoValidacao = new ResultadoValidacao();
        $resultadoValidacao->adicionarValidacao($this->validarEmail());
        $resultadoValidacao->adicionarValidacao($this->validarSenha());

        return $resultadoValidacao;
    }

    public function validarCadastro() : ResultadoValidacao
    {
        $resultadoValidacao = new ResultadoValidacao();
        $resultadoValidacao->adicionarValidacao($this->validarNome());
        $resultadoValidacao->adicionarValidacao($this->validarEmail());
        $resultadoValidacao->adicionarValidacao($this->validarSenha());
        $resultadoValidacao->adicionarValidacao($this->validarCPF());
        $resultadoValidacao->adicionarValidacao($this->validarRG());
        $resultadoValidacao->adicionarValidacoes($this->validarEndereco());
        $resultadoValidacao->adicionarValidacoes($this->validarTelefones());
        $resultadoValidacao->adicionarValidacao($this->validarTipoUsuario());

        return $resultadoValidacao;
    }

    public function validarAtualizacao() : ResultadoValidacao
    {
        $resultadoValidacao = new ResultadoValidacao();
        $resultadoValidacao->adicionarValidacao($this->validarNome());
        $resultadoValidacao->adicionarValidacoes($this->validarEndereco());
        $resultadoValidacao->adicionarValidacoes($this->validarTelefones());
        $resultadoValidacao->adicionarValidacao($this->validarTipoUsuario());

        return $resultadoValidacao;
    }


    private function validarNome() : Validacao
    {
        $validacao = new Validacao("nome");
        $result =  ValidacaoUtil::tamanhoEntre($this->nome, 10, 128);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O Nome precisa ter entre 10 e 128 caracteres"));
        return $validacao;
    }

    private function validarEmail() : Validacao
    {
        $validacao = new Validacao("email");
        $result = ValidacaoUtil::email($this->email);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O E-mail {$this->email} é inválido"));
        return $validacao;
    }

    private function validarSenha() : Validacao
    {
        $validacao = new Validacao("senha");
        $result = ValidacaoUtil::tamanhoEntre($this->senha, 6, 32);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "A Senha precisa ter entre 6 e 32 caracteres"));
        return $validacao;
    }

    private function validarCPF() : Validacao
    {
        $validacao = new Validacao("cpf");
        $result = ValidacaoUtil::cpf($this->cpf);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O CPF informado é inválido"));
        return $validacao;
    }

    private function validarRG() : Validacao
    {
        $validacao = new Validacao("rg");
        $result = ValidacaoUtil::tamanhoEntre($this->rg, 8, 16);
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "O RG informado é inválido"));
        return $validacao;
    }

    private function validarEndereco() : array
    {
        return $this->endereco->validarCadastro();
    }

    private function validarTelefones() : array
    {
        $validacoes = [];
        if (isset($this->telefones)) {
            foreach($this->telefones as $telefone) {
                $validacoes[] = $telefone->validarTelefone();
            }
        } else {
            $telefoneValidacao = new Validacao("telefone");
            $telefoneValidacao->adicionarMensagem("Pelo Menos 1 telefone deve ser informado");
            $validacoes[] = $telefoneValidacao;  
        }
        
        return $validacoes;
    }

    private function validarTipoUsuario() : Validacao
    {
        $validacao = new Validacao("tipo_usuario");
        $result = $this->tipoUsuario != self::COMUM || $this->tipoUsuario != self::MINISTERIO_SAUDE;
        $validacao->adicionarMensagem(ValidacaoUtil::aoErro($result, "{$this->tipoUsuario} é um tipo de usuário inválido!"));
        return $validacao;
    }
    
}