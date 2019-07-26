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

use Swoft\Auth\AuthResult;

/**
 * Interface AccountTypeInterface
 *
 * @since 2.0
 */
interface AccountTypeInterface
{
    public const LOGIN_IDENTITY = 'identity';

    public const LOGIN_CREDENTIAL = 'credential';

    public function login(array $data): AuthResult;

    public function authenticate(string $identity): bool;
}
