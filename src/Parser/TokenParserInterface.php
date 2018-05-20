<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/5/20
 * Time: 下午9:36
 * @author April2 <ott321@yeah.net>
 */

namespace Swoft\Auth\Parser;


use Swoft\Auth\Bean\AuthSession;

interface TokenParserInterface
{
    /**
     * @param AuthSession $session
     * @return string
     */
    public function getToken(AuthSession $session):string ;

    /**
     * @param string $token
     * @return AuthSession
     */
    public function getSession(string $token):AuthSession ;


}