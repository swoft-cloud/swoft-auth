<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft;
use Swoft\Auth\Contract\AuthServiceInterface;
use Swoft\Auth\ErrorCode;
use Swoft\Auth\Exception\AuthException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Contract\MiddlewareInterface;

/**
 * @Bean()
 */
class AclMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestHandler = $request->getAttributes()['requestHandler'][2]['handler'] ?? '';
        $service        = Swoft::getBean(AuthServiceInterface::class);

        if (!$service instanceof AuthServiceInterface) {
            throw new AuthException(
                'AuthService should implement Swoft\Auth\Contract\AuthServiceInterface', ErrorCode::POST_DATA_NOT_PROVIDED);
        }

        if (!$service->auth($requestHandler, $request)) {
            throw new AuthException('ACCESS_DENIED', ErrorCode::ACCESS_DENIED);
        }

        return $handler->handle($request);
    }
}
