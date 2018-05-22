<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/5/22
 * Time: 下午9:21
 * @author April2 <ott321@yeah.net>
 */

namespace Swoft\Auth\Mapping;


use Psr\Http\Message\ServerRequestInterface;

interface AuthServiceInterface
{

    /**
     * <code>
     * $controller = $this->getHandlerArray($requestHandler)[0];
     * $method = $this->getHandlerArray($requestHandler)[1];
     * $id = $this->getUserIdentity();
     * if ($id) {
     * return true;
     * }
     * return false;
     * </code>
     *
     * @param string $requestHandler
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function auth(string $requestHandler, ServerRequestInterface $request): bool;

}