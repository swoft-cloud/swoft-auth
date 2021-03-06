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
use Swoft\Auth\Contract\AuthorizationParserInterface;
use Swoft\Auth\ErrorCode;
use Swoft\Auth\Exception\AuthException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Server\Contract\MiddlewareInterface;

/**
 * @Bean()
 */
class AuthMiddleware implements MiddlewareInterface
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
        $parser = Swoft::getBean(AuthorizationParserInterface::class);
        if (!$parser instanceof AuthorizationParserInterface) {
            throw new AuthException(ErrorCode::POST_DATA_NOT_PROVIDED,
                'AuthorizationParser should implement Swoft\Auth\Contract\AuthorizationParserInterface');
        }

        $request = $parser->parse($request);

        return $handler->handle($request);
    }
}
