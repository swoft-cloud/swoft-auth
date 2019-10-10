<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Auth\UnitManager;

use Swoft;
use Swoft\Auth\AuthConst;
use Swoft\Auth\AuthManager;
use Swoft\Auth\Contract\AuthManagerInterface;
use Swoft\Http\Message\Request;
use SwoftTest\Auth\Unit\AbstractTestCase;

class AuthManagerTest extends AbstractTestCase
{
    protected function registerRoute(): void
    {
        /** @var Swoft\Http\Server\Router\Router $router */
        $router = Swoft::getBean('httpRouter');
        $router->post('/login', function (Request $request) {
            $name = $request->getAttribute(AuthConst::BASIC_USER_NAME);
            $pd   = $request->getAttribute(AuthConst::BASIC_PASSWORD);
            /** @var TestManager $manager */
            $manager = Swoft::getBean(AuthManagerInterface::class);
            $session = $manager->testLogin($name, $pd);
            return ['token' => $session->getToken()];
        });

        $router->get('/test', function (Request $request) {
            return 'pass';
        });
    }

    /**
     * @covers AuthManager::login()
     */
    public function testLogin(): void
    {
        $username = 'user';
        $password = '123';
        $parser   = base64_encode($username . ':' . $password);
        $response = $this->request('POST', '/login', [], self::ACCEPT_JSON, ['Authorization' => 'Basic ' . $parser],
            'test');
        $res      = $response->getBody()->getContents();
        $token    = json_decode($res, true)['token'];
        $response = $this->request('GET', '/test', [], self::ACCEPT_JSON, ['Authorization' => 'Bearer ' . $token],
            'test');
        $res      = $response->getBody()->getContents();
        $result   = json_decode($res, true)['data'] ?? '';
        $this->assertNotEquals('', $result);
    }
}
