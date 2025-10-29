<?php

namespace App\Services;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token;
use DateTimeImmutable;
use App\Models\User;
use Lcobucci\JWT\UnencryptedToken;

class JwtAuthService
{
    private Configuration $config;

    public function __construct()
    {
        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(env('JWT_SECRET'))
        );
    }

    public function createToken(User $user): string
    {
        $now = new DateTimeImmutable();

        return $this->config->builder()
            ->issuedBy(env('APP_NAME'))
            ->permittedFor(env('APP_NAME'))
            ->issuedAt($now)
            ->expiresAt($now->modify('+1 hour'))
            ->relatedTo($user->id)
            ->withClaim('user_uuid', $user->uuid)
            ->getToken($this->config->signer(), $this->config->signingKey())
            ->toString();
    }

    public function decodeToken(string $jwt): Token
    {
        return $this->config->parser()->parse($jwt);
    }

    public function authenticate(string $jwt): ?User
    {
        /** @var UnencryptedToken $token */
        $token = $this->decodeToken($jwt);

        $claims = $token->claims();
        $uuid = $claims->get('user_uuid');

        return User::where('uuid', $uuid)->first();
    }
}
