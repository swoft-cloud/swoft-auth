<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/5/20
 * Time: 下午5:34
 * @author April2 <ott321@yeah.net>
 */

namespace Swoft\Auth\Exception;

use Swoft\Exception\RuntimeException;
use Throwable;

class AuthException extends RuntimeException
{

    public function __construct(int $code = 0, string $message = "", Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}