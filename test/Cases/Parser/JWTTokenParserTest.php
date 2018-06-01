<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Auth\Parser;

use Swoft\App;
use Swoft\Auth\AuthAccount;
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
    public function testGetToken()
    {
        $parser = App::getBean(JWTTokenParser::class);
        $session = new AuthSession();
        $session->setIdentity(1);
        $session->setExpirationTime(time()+10);
        $session->setAccountTypeName(AuthAccount::class);
        $token = $parser->getToken($session);
        $this->assertStringStartsWith('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9', $token);
        return $token;
    }

    /**
     * @test
     * @covers JWTTokenParser::getSession()
     */
    public function testGetSession()
    {
        $token = $this->testGetToken();
        $parser = App::getBean(JWTTokenParser::class);
        /** @var AuthSession $session */
        $session = $parser->getSession($token);
        $this->assertEquals(1, $session->getIdentity());
    }
}
