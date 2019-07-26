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
use Swoft\Auth\AuthConst;
use Swoft\Http\Message\Server\Request;
use Swoft\Http\Server\Router\HandlerMapping;
use SwoftTest\Auth\UnitAbstractTestCase;

/**
 * Class BasicAuthParserTest
 * @package SwoftTest\Auth\UnitParser
 */
class BasicAuthParserTest extends AbstractTestCase
{
    protected function registerRoute()
    {
        /** @var HandlerMapping $router */
        $router = Swoft::getBean('httpRouter');
        $router->get('/', function (Request $request) {
            $name = $request->getAttribute(AuthConst::BASIC_USER_NAME);
            $pd = $request->getAttribute(AuthConst::BASIC_PASSWORD);
            return ['username' => $name, 'password' => $pd];
        });
    }

    /**
     * @covers BasicAuthHandler::handle()
     */
    public function testHandle()
    {
        $username = 'user';
        $password = '123';
        $parser = base64_encode($username . ':' . $password);
        $response = $this->request('GET', '/', [], self::ACCEPT_JSON, ['Authorization' => 'Basic ' . $parser], 'test');
        $res = $response->getBody()->getContents();
        $this->assertEquals(json_decode($res, true), ['username' => $username, 'password' => $password]);
    }
}
