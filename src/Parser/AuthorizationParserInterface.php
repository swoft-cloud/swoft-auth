<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/5/20
 * Time: 下午8:48
 * @author April2 <ott321@yeah.net>
 */

namespace Swoft\Auth\Parser;


use Psr\Http\Message\ServerRequestInterface;

interface AuthorizationParserInterface
{

    public function parse(ServerRequestInterface $request);

}