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
use Swoft\App;
use Swoft\Auth\AuthManager;
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Auth\Constants\ServiceConstants;
use Swoft\Auth\Mapping\AuthHandleInterface;
use Swoft\Bean\Annotation\Bean;

/**
 * Class BearerTokenParser
 * @package Swoft\Auth\Parser
 * @Bean()
 */
class BearerTokenParser implements AuthHandleInterface
{

    const NAME = 'Bearer';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function parse(ServerRequestInterface $request): ServerRequestInterface
    {
        $token = $this->getToken($request);
        /** @var AuthManager $manager */
        $manager = App::getBean(ServiceConstants::AUTH_MANAGER);
        if($token){
            $res = $manager->authenticateToken($token);
            $request = $request->withAttribute(AuthConstants::IS_LOGIN,$res);
        }
        return $request;
    }

    protected function getToken(ServerRequestInterface $request)
    {
        $authHeader = $request->getHeaderLine(AuthConstants::HEADER_KEY) ?? '';
        $authQuery = $request->getQueryParams()['token'] ?? '';
        return $authQuery ? $authQuery : $this->parseValue($authHeader);
    }

    protected function parseValue($string){
        if (strpos(trim($string), self::NAME) !== 0) {
            return null;
        }
        return preg_replace('/.*\s/', '', $string);
    }

}