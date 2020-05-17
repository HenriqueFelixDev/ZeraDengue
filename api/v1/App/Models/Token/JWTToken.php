<?php

namespace App\Models\Token;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JWTToken implements IToken
{
    public function gerarToken($usuario) : ?String
    {
        $signer = new Sha256();

        $time = time();
        $token = (new Builder())->setIssuer(BASE_URL)
                    ->permittedFor(BASE_URL)
                    ->setIssuedAt($time)
                    ->setExpiration($time + 86400)
                    ->withClaim("uid", $usuario->usuario_id)
                    ->withClaim("unm", $usuario->nome)
                    ->getToken($signer, new Key(JWT_KEY));
        return $token;
    }
    public function validarToken(?String $token) : bool
    {
        try{
            $signer = new Sha256();

            $validation = new ValidationData();
            $validation->setIssuer(BASE_URL);
            $validation->setAudience(BASE_URL);

            $token = (new Parser())->parse((string) $token);
            return $token->validate($validation) && $token->verify($signer, new Key(JWT_KEY));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @Override
     */
    public function obterDados(?String $token): ?array
    {
        $token = (new Parser())->parse($token);
        return $token->getClaims();
    }
}