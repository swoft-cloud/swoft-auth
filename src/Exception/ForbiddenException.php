<?php declare(strict_types=1);

namespace Swoft\Auth\Exception;

use Throwable;

/**
 * Class ForbiddenException
 *
 * @since 2.0
 */
class ForbiddenException extends AuthException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param Throwable $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = '', $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
