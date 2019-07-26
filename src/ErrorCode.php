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
 * Class ErrorCode
 *
 * @since 2.0
 */
class ErrorCode
{
    /**
     * 通用
     */
    public const GENERAL_SYSTEM = 1010;

    public const GENERAL_NOT_IMPLEMENTED = 1020;

    public const GENERAL_NOT_FOUND = 1030;

    /**
     * 用户认证
     */
    public const AUTH_INVALID_ACCOUNT_TYPE = 2010;

    public const AUTH_LOGIN_FAILED = 2020;

    public const AUTH_TOKEN_INVALID = 2030;

    public const AUTH_SESSION_EXPIRED = 2040;

    public const AUTH_SESSION_INVALID = 2050;

    /**
     * 访问控制
     */
    public const ACCESS_DENIED = 3010;

    /**
     * 客户端错误
     */
    public const DATA_FAILED = 4010;

    public const DATA_NOT_FOUND = 4020;

    /**
     * 服务器错误
     */
    public const POST_DATA_NOT_PROVIDED = 5010;

    public const POST_DATA_INVALID = 5020;
}
