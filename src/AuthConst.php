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

/**
 * Class AuthConst
 *
 * @since 2.0
 */
class AuthConst
{
    public const IS_LOGIN = 'isLogin';

    public const IDENTITY = 'identity';

    public const HEADER_KEY = 'Authorization';

    public const BASIC_USER_NAME = 'basicUsername';

    public const BASIC_PASSWORD = 'basicPassword';

    public const AUTH_SESSION = 'authSession';

    public const EXTEND_DATA = 'extendedData';
}
