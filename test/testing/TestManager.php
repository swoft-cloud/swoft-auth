<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Auth\Testing;

use Swoft\Auth\AuthManager;
use Swoft\Auth\AuthSession;
use Swoft\Redis\Redis;

class TestManager extends AuthManager
{
    protected $cacheClass = Redis::class;

    protected $cacheEnable = true;

    public function testLogin(string $username, string $password): AuthSession
    {
        return $this->login(TestAccount::class, [
            $username,
            $password
        ]);
    }
}
