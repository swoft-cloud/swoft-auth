<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/5/23
 * Time: 上午12:44
 * @author April2 <ott321@yeah.net>
 */

namespace SwoftTest\Auth\Parser;


use Swoft\App;
use Swoft\Auth\Bean\AuthSession;
use Swoft\Auth\Parser\JWTTokenParser;
use SwoftTest\Auth\AbstractTestCase;

class JWTTokenParserTest extends AbstractTestCase
{
    /**
     * @test
     * @covers JWTTokenParser::getToken()
     * @return string
     */
    public function testGetToken(){
        $parser = App::getBean(JWTTokenParser::class);
        $session = new AuthSession();
        $session->setIdentity(1);
        $session->setExpirationTime(time()+10);
        $token = $parser->getToken($session);
        $this->assertStringStartsWith("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9",$token);
        return $token;
    }

    /**
     * @test
     * @covers JWTTokenParser::getSession()
     */
    public function testGetSession(){
        $token = $this->testGetToken();
        $parser = App::getBean(JWTTokenParser::class);
        $session = $parser->getSession($token);
        $this->assertEquals(1,$session->getIdentity());
    }

}