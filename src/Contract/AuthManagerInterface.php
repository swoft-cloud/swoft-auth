<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Contract;

use Swoft\Auth\AuthSession;

interface AuthManagerInterface
{
    public function login(string $accountTypeName, array $data): AuthSession;

    public function authenticateToken(string $token): bool;
}
