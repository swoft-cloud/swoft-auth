<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/5/20
 * Time: 下午4:25
 * @author April2 <ott321@yeah.net>
 */

namespace Swoft\Auth\Mapping;

use Psr\Http\Message\ServerRequestInterface;

interface AuthHandleInterface
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function parse(ServerRequestInterface $request): ServerRequestInterface;

}