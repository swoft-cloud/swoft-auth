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
use Swoft\Auth\AuthUserService;
use Swoft\Auth\Constants\ServiceConstants;
use Swoft\Auth\Parser\BearerTokenParser;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Router\HandlerMapping;
use SwoftTest\Auth\AbstractTestCase;

class BearerTokenParserTest extends AbstractTestCase
{
    protected function registerRoute()
    {
        /** @var HandlerMapping $router */
        $router = App::getBean('httpRouter');
        $router->get('/test', function (Request $request) {
            /** @var AuthUserService $service */
            $service  = App::getBean(ServiceConstants::AUTH_USERS_SERVICE);
            $session = $service->getSession();
            return ['id'=>$session->getIdentity()];
        });
    }

    /**
     * @test
     * @covers AuthManager::authenticateToken()
     * @covers BearerTokenParser::parse()
     * @covers AuthAccount::authenticate()
     */
    public function testParse()
    {
        $jwt = new JWTTokenParserTest();
        $token = $jwt->testGetToken();
        $response = $this->request('GET', '/test', [], self::ACCEPT_JSON, ['Authorization' => 'Bearer ' . $token], 'test');
        $res  = $response->getBody()->getContents();
        $this->assertEquals(json_decode($res, true), ['id' => 1]);
    }
}
