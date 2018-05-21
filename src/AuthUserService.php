<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/5/21
 * Time: 上午9:57
 * @author April2 <ott321@yeah.net>
 */

namespace Swoft\Auth;


use Swoft\Auth\Bean\AuthSession;
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Core\RequestContext;

class AuthUserService
{


    public function getUserIdentity(): string
    {
        return $this->getSession()->getIdentity();
    }


    public function getUserExtendData(): array
    {
        return $this->getSession()->getExtendedData();
    }


    public function getSession(): AuthSession
    {
        return RequestContext::getContextDataByKey(AuthConstants::AUTH_SESSION);
    }

    /**
     * @param string $controller 控制器名
     * @param string $action 方法名
     * @return bool
     */
    public function auth(string $controller,string $action): bool
    {
        $id = $this->getUserIdentity();
        if ($id) {
            return true;
        }
        return false;
    }

}