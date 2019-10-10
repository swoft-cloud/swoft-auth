<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Auth\UnitParser;

use Swoft;
use Swoft\Auth\AuthSession;
use Swoft\Auth\Parser\JWTTokenParser;
use SwoftTest\Auth\Testing\TestAccount;
use SwoftTest\Auth\Unit\AbstractTestCase;

class JWTTokenParserTest extends AbstractTestCase
{
    /**
     * @covers JWTTokenParser::getToken()
     * @return string
     */
    public function testGetToken(): string
    {
        $parser  = Swoft::getBean(JWTTokenParser::class);
        $session = new AuthSession();
        $session->setIdentity('2');
        $session->setExpirationTime(time() + 10);
        $session->setAccountTypeName(TestAccount::class);
        $token = $parser->getToken($session);
        $this->assertStringStartsWith('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9', $token);
        return $token;
    }

    /**
     * @covers JWTTokenParser::getSession()
     */
    public function testGetSession(): void
    {
        $token  = $this->testGetToken();
        $parser = Swoft::getBean(JWTTokenParser::class);
        /** @var AuthSession $session */
        $session = $parser->getSession($token);
        $this->assertEquals(2, $session->getIdentity());
    }
}
