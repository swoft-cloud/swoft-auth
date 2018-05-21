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
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Auth\Mapping\AuthHandleInterface;
use Swoft\Bean\Annotation\Bean;

/**
 * Class BasicAuthParser
 * @package Swoft\Auth\Parser
 * @Bean()
 */
class BasicAuthParser implements AuthHandleInterface
{

    const NAME = 'Basic';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function parse(ServerRequestInterface $request): ServerRequestInterface
    {
        $authHeader = $request->getHeaderLine(AuthConstants::HEADER_KEY) ?? '';
        $basic = $this->parseValue($authHeader);
        if($basic){
            $request = $request
                ->withAttribute(AuthConstants::BASIC_USER_NAME,$this->getUsername($basic))
                ->withAttribute(AuthConstants::BASIC_PASSWORD,$this->getPassword($basic));
        }
        return $request;
    }

    protected function getUsername(array $basic)
    {
        return $basic[0]??'';
    }

    protected function getPassword(array $basic){
        return $basic[1]??'';
    }

    protected function parseValue($string):array
    {
        if (strpos(trim($string), self::NAME) !== 0) {
            return null;
        }
        $val =  preg_replace('/.*\s/', '', $string);
        if(!$val){
            return null;
        }
        return  explode(':',base64_decode($val));
    }
}