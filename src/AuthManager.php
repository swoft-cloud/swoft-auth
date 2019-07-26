<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionException;
use Swoft;
use Swoft\Auth\Contract\AccountTypeInterface;
use Swoft\Auth\Contract\AuthManagerInterface;
use Swoft\Auth\Contract\TokenParserInterface;
use Swoft\Auth\Exception\AuthException;
use Swoft\Auth\Exception\RuntimeException;
use Swoft\Auth\Parser\JWTTokenParser;
use Swoft\Bean\Exception\ContainerException;
use Throwable;
use function context;
use function json_encode;

/**
 * Class AuthManager
 *
 * @since 2.0
 */
class AuthManager implements AuthManagerInterface
{
    /**
     * @var string
     */
    protected $prefix = 'swoft.token.';

    /**
     * @var int
     */
    protected $sessionDuration = 86400;

    /**
     * @var bool
     */
    protected $cacheEnable = false;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var string
     */
    protected $cacheClass = '';

    /**
     * @var string
     */
    protected $tokenParserClass = JWTTokenParser::class;

    /**
     * @var TokenParserInterface
     */
    private $tokenParser;

    public function getSessionDuration(): int
    {
        return $this->sessionDuration;
    }

    public function setSessionDuration($time): void
    {
        $this->sessionDuration = $time;
    }

    /**
     * @return AuthSession|mixed
     */
    public function getSession()
    {
        return context()->get(AuthConst::AUTH_SESSION);
    }

    public function setSession(AuthSession $session): void
    {
        context()->set(AuthConst::AUTH_SESSION, $session);
    }

    /**
     * Check if a user is currently logged in
     */
    public function isLoggedIn(): bool
    {
        return $this->getSession() instanceof AuthSession;
    }

    public function login(string $accountTypeName, array $data): AuthSession
    {
        if (!$account = $this->getAccountType($accountTypeName)) {
            throw new AuthException(ErrorCode::AUTH_INVALID_ACCOUNT_TYPE);
        }

        $result = $account->login($data);
        if (!$result instanceof AuthResult || $result->getIdentity() === '') {
            throw new AuthException(ErrorCode::AUTH_LOGIN_FAILED);
        }

        $session = $this->generateSession($accountTypeName, $result->getIdentity(), $result->getExtendedData());
        $this->setSession($session);

        if ($this->cacheEnable === true) {
            try {
                $this->getCacheClient()->set($this->getCacheKey($session->getIdentity(), $session->getExtendedData()),
                    $session->getToken(), $this->getSessionDuration());
            } catch (\InvalidArgumentException $e) {
                $err = sprintf('%s Invalid Argument : %s', $session->getIdentity(), $e->getMessage());
                throw new AuthException(ErrorCode::POST_DATA_NOT_PROVIDED, $err);
            }
        }

        return $session;
    }

    protected function getCacheKey(string $identity, array $extendedData): string
    {
        if (empty($extendedData)) {
            return $this->prefix . $identity;
        }

        $str = json_encode($extendedData);

        return $this->prefix . $identity . '.' . md5($str);
    }

    public function generateSession(string $accountTypeName, string $identity, array $data = []): AuthSession
    {
        $startTime = time();
        $exp       = $startTime + (int)$this->sessionDuration;
        $session   = new AuthSession();

        $session->setExtendedData($data)->setExpirationTime($exp)->setCreateTime($startTime)->setIdentity($identity)
                ->setAccountTypeName($accountTypeName);
        $token = $this->getTokenParser()->getToken($session);
        $session->setToken($token);

        return $session;
    }

    /**
     * @param string $name
     *
     * @return AccountTypeInterface|null
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function getAccountType(string $name): ?AccountTypeInterface
    {
        if (!Swoft::hasBean($name)) {
            return null;
        }

        $account = Swoft::getBean($name);
        if (!$account instanceof AccountTypeInterface) {
            return null;
        }

        return $account;
    }

    /**
     * @return TokenParserInterface
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function getTokenParser(): TokenParserInterface
    {
        if (!$this->tokenParser instanceof TokenParserInterface) {
            if (!Swoft::hasBean($this->tokenParserClass)) {
                throw new RuntimeException('Cannot find tokenParserClass');
            }

            $tokenParser = Swoft::getBean($this->tokenParserClass);
            if (!$tokenParser instanceof TokenParserInterface) {
                throw new RuntimeException("TokenParser need implements Swoft\Auth\Contract\TokenParserInterface ");
            }

            $this->tokenParser = $tokenParser;
        }

        return $this->tokenParser;
    }

    /**
     * @return CacheInterface
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function getCacheClient(): CacheInterface
    {
        if (!$this->cache instanceof CacheInterface) {
            if (!Swoft::hasBean($this->cacheClass)) {
                throw new RuntimeException('Can`t find cacheClass');
            }

            $cache = Swoft::getBean($this->cacheClass);
            if (!$cache instanceof CacheInterface) {
                throw new RuntimeException('CacheClient need implements Psr\SimpleCache\CacheInterface');
            }

            $this->cache = $cache;
        }

        return $this->cache;
    }

    /**
     * @param string $token
     *
     * @return bool
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function authenticateToken(string $token): bool
    {
        try {
            /** @var AuthSession $session */
            $session = $this->getTokenParser()->getSession($token);
        } catch (Throwable $e) {
            throw new AuthException(ErrorCode::AUTH_TOKEN_INVALID);
        }

        if (!$session) {
            return false;
        }

        if ($session->getExpirationTime() < time()) {
            throw new AuthException(ErrorCode::AUTH_SESSION_EXPIRED);
        }

        if (!$account = $this->getAccountType($session->getAccountTypeName())) {
            throw new AuthException(ErrorCode::AUTH_SESSION_INVALID);
        }

        if (!$account->authenticate($session->getIdentity())) {
            throw new AuthException(ErrorCode::AUTH_TOKEN_INVALID);
        }

        if ($this->cacheEnable === true) {
            try {
                $cache = $this->getCacheClient()->get($this->getCacheKey($session->getIdentity(),
                    $session->getExtendedData()));
                if (!$cache || $cache !== $token) {
                    throw new AuthException(ErrorCode::AUTH_TOKEN_INVALID);
                }
            } catch (InvalidArgumentException $e) {
                $err = sprintf('Identity : %s ,err : %s', $session->getIdentity(), $e->getMessage());
                throw new AuthException(ErrorCode::POST_DATA_NOT_PROVIDED, $err);
            }
        }

        $this->setSession($session);
        return true;
    }
}
