<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Parser;

use Firebase\JWT\JWT;
use Swoft\Auth\AuthSession;
use Swoft\Auth\Contract\TokenParserInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Config\Annotation\Mapping\Config;

/**
 * @Bean()
 */
class JWTTokenParser implements TokenParserInterface
{
    public const ALGORITHM_HS256 = 'HS256';

    public const ALGORITHM_HS512 = 'HS512';

    public const ALGORITHM_HS384 = 'HS384';

    public const ALGORITHM_RS256 = 'RS256';

    /**
     * @Config("auth.jwt.algorithm")
     *
     * @var string
     */
    protected $algorithm = self::ALGORITHM_HS256;

    /**
     * @Config("auth.jwt.secret")
     *
     * @var string
     */
    protected $secret = 'swoft';

    /**
     * @param AuthSession $session
     *
     * @return string
     */
    public function getToken(AuthSession $session): string
    {
        $tokenData = $this->create($session->getAccountTypeName(), $session->getIdentity(), $session->getCreateTime(),
            $session->getExpirationTime(), $session->getExtendedData());
        return $this->encode($tokenData);
    }

    public function getSession(string $token): AuthSession
    {
        $tokenData = $this->decode($token);
        return (new AuthSession())->setAccountTypeName($tokenData->iss)->setIdentity($tokenData->sub)
                                  ->setCreateTime($tokenData->iat)->setExpirationTime($tokenData->exp)->setToken($token)
                                  ->setExtendedData((array)$tokenData->data);
    }

    protected function create(string $issuer, string $user, int $iat, int $exp, array $data): array
    {
        return [
            /*
            The iss (issuer) claim identifies the principal
            that issued the JWT. The processing of this claim
            is generally application specific.
            The iss value is a case-sensitive string containing
            a StringOrURI value. Use of this claim is OPTIONAL.
            ------------------------------------------------
            */ 'iss'              => $issuer,

            /*
            The sub (subject) claim identifies the principal
            that is the subject of the JWT. The Claims in a
            JWT are normally statements about the subject.
            The subject value MUST either be scoped to be
            locally unique in the context of the issuer or
            be globally unique. The processing of this claim
            is generally application specific. The sub value
            is a case-sensitive string containing a
            StringOrURI value. Use of this claim is OPTIONAL.
            ------------------------------------------------
            */ 'sub'  => $user,

            /*
            The iat (issued at) claim identifies the time at
            which the JWT was issued. This claim can be used
            to determine the age of the JWT. Its value MUST
            be a number containing a NumericDate value.
            Use of this claim is OPTIONAL.
            ------------------------------------------------
            */ 'iat'  => $iat,

            /*
            The exp (expiration time) claim identifies the
            expiration time on or after which the JWT MUST NOT
            be accepted for processing. The processing of the
            exp claim requires that the current date/time MUST
            be before the expiration date/time listed in the
            exp claim. Implementers MAY provide for some small
            leeway, usually no more than a few minutes,
            to account for clock skew. Its value MUST be a
            number containing a NumericDate value.
            Use of this claim is OPTIONAL.
            ------------------------------------------------
            */ 'exp'  => $exp,

            /*
             Expand data
             ------------------------------------------------
            */ 'data' => $data,
        ];
    }

    public function encode($token): string
    {
        return (string)JWT::encode($token, $this->secret, $this->algorithm);
    }

    public function decode($token)
    {
        return JWT::decode($token, $this->secret, [$this->algorithm]);
    }
}
