<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/5/22
 * Time: 下午9:22
 * @author April2 <ott321@yeah.net>
 */

namespace Swoft\Auth\Mapping;


use Swoft\Auth\Bean\AuthSession;

interface AuthManagerInterface
{

    /**
     * @param $accountTypeName
     * @param array $data
     * @return AuthSession
     */
    public function login(string $accountTypeName, array $data):AuthSession;

    /**
     * @param $token
     * @return bool
     */
    public function authenticateToken(string $token):bool ;

}