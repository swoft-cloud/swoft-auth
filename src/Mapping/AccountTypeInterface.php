<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/4/18
 * Time: 下午3:15
 */

namespace Swoft\Auth\Mapping;


use Swoft\Auth\Bean\AuthResult;

interface AccountTypeInterface
{

    const LOGIN_DATA_USERNAME = "username";

    const LOGIN_DATA_PASSWORD = "password";

    /**
     * @param array $data Login data
     *
     * @return AuthResult|null
     */
    public function login(array $data):AuthResult;

    /**
     * @param string $identity Identity
     *
     * @return bool Authentication successful
     */
    public function authenticate(string $identity) :bool ;

}