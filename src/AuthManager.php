<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/5/20
 * Time: 下午4:11
 * @author April2 <ott321@yeah.net>
 */

namespace Swoft\Auth;

use App\Component\Auth\Account\AccountTypeInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Swoft\App;
use Swoft\Auth\Bean\AuthResult;
use Swoft\Auth\Bean\AuthSession;
use Swoft\Auth\Exception\AuthException;
use Swoft\Auth\Helper\ErrorCode;
use Swoft\Auth\Parser\TokenParserInterface;
use Swoft\Bean\Annotation\Value;
use Swoft\Core\RequestContext;

/**
 * Class AuthManager
 * @package Swoft\Auth
 */
class AuthManager
{

    const KEY = "authSession";

    /**
     * @Value("${config.auth.cache.prefix}")
     * @var string
     */
    private $prefix = "token_";

    /**
     * @var int
     * @Value("${config.auth.token.lifetime}")
     */
    protected $sessionDuration = 86400;

    /**
     * @var bool
     * @Value("${config.auth.cache.enable}")
     */
    protected $cacheEnable = false;

    /**
     * @Value("${config.auth.cache}")
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @Value("${config.auth.token.parser}")
     * @var TokenParserInterface
     */
    protected $tokenParser;


    public function getSessionDuration()
    {
        return $this->sessionDuration;
    }

    public function setSessionDuration($time)
    {
        $this->sessionDuration = $time;
    }

    /**
     * @return AuthSession;
     */
    public function getSession()
    {
        return RequestContext::getContextDataByKey(self::KEY);
    }

    public function setSession(AuthSession $session)
    {
        RequestContext::setContextData([self::KEY => $session]);
    }

    /**
     * @return bool
     *
     * Check if a user is currently logged in
     */
    public function loggedIn()
    {
        return $this->getSession() instanceof AuthSession;
    }

    /**
     * @param $accountTypeName
     * @param array $data
     * @return AuthSession
     */
    public function login(string $accountTypeName, array $data)
    {
        if (!$account = $this->getAccountType($accountTypeName)) {
            throw new AuthException(ErrorCode::AUTH_INVALID_ACCOUNT_TYPE);
        }
        /** @var AuthResult $result */
        $result = $account->login($data);
        if (!$result) {
            throw new AuthException(ErrorCode::AUTH_LOGIN_FAILED);
        }
        $identity = $result->getIdentity();
        $session = $this->generateSession($accountTypeName, $identity, $data);
        $this->setSession($session);
        if ($this->cacheEnable === true) {
            try {
                $this->cache->set($this->getCacheKey($identity), $session->getToken(), $session->getExpirationTime());
            } catch (InvalidArgumentException $e) {
                $err = sprintf("%s 参数无效",$session->getIdentity());
                throw new AuthException(ErrorCode::POST_DATA_NOT_PROVIDED,$err);
            }
        }
        return $session;
    }


    protected function getCacheKey($identity)
    {
        return $this->prefix . $identity;
    }

    /**
     * @param string $accountTypeName
     * @param string $identity
     * @param array $data
     * @return AuthSession
     */
    public function generateSession(string $accountTypeName, string $identity, array $data = [])
    {
        $startTime = time();
        $exp = $startTime + (int)$this->sessionDuration;
        $session = new AuthSession();
        $session
            ->setExtendedData($data)
            ->setExpirationTime($exp)
            ->setCreateTime($startTime)
            ->setIdentity($identity)
            ->setAccountTypeName($accountTypeName);
        $session->setExtendedData($data);
        $token = $this->tokenParser->getToken($session);
        $session->setToken($token);
        return $session;
    }

    /**
     * @param $name
     * @return AccountTypeInterface|null
     */
    public function getAccountType($name)
    {
        if (!App::hasBean($name)) {
            return null;
        }
        $account = App::getBean($name);
        if (!$account instanceof AccountTypeInterface) {
            return null;
        }
        return $account;
    }

    /**
     * @param $token
     * @return bool
     * @throws AuthException
     */
    public function authenticateToken($token)
    {
        try {
            /** @var AuthSession $session */
            $session = $this->tokenParser->getSession($token);
        } catch (\Exception $e) {
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
                $cache = $this->cache->get($this->getCacheKey($session->getIdentity()));
                if (!$cache || $cache !== $token) {
                    throw new AuthException(ErrorCode::AUTH_TOKEN_INVALID);
                }
            } catch (InvalidArgumentException $e) {
                $err = sprintf("%s 参数无效",$session->getIdentity());
                throw new AuthException(ErrorCode::POST_DATA_NOT_PROVIDED,$err);
            }
        }
        $this->setSession($session);
        return true;
    }


}