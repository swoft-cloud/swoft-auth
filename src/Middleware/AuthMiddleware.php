<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/5/20
 * Time: 下午4:09
 * @author April2 <ott321@yeah.net>
 */

namespace Swoft\Auth\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\App;
use Swoft\Auth\Parser\AuthorizationHeaderParser;
use Swoft\Bean\Annotation\Bean;
use Swoft\Http\Message\Middleware\MiddlewareInterface;

/**
 * Class AuthMiddleware
 * @package Swoft\Auth\Middleware
 * @Bean()
 */
class AuthMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AuthorizationHeaderParser $parser */
        $parser = App::getBean("AuthRequestHeaderParser");
        $request = $parser->parse($request);
        $response = $handler->handle($request);
        return $response;
    }
}