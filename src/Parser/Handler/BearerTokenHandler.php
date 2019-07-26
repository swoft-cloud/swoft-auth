<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Parser\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Swoft;
use Swoft\Auth\AuthConst;
use Swoft\Auth\Contract\AuthHandlerInterface;
use Swoft\Auth\Contract\AuthManagerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * @Bean()
 */
class BearerTokenHandler implements AuthHandlerInterface
{
    const NAME = 'Bearer';

    public function handle(ServerRequestInterface $request): ServerRequestInterface
    {
        $token = $this->getToken($request);
        /** @var AuthManagerInterface $manager */
        $manager = Swoft::getBean(AuthManagerInterface::class);
        if ($token) {
            $res     = $manager->authenticateToken($token);
            $request = $request->withAttribute(AuthConst::IS_LOGIN, $res);
        }
        return $request;
    }

    protected function getToken(ServerRequestInterface $request)
    {
        $authHeader = $request->getHeaderLine(AuthConst::HEADER_KEY) ?? '';
        $authQuery  = $request->getQueryParams()['token'] ?? '';
        return $authQuery ?: $this->parseValue($authHeader);
    }

    protected function parseValue($string)
    {
        if (strpos(trim($string), self::NAME) !== 0) {
            return null;
        }
        return preg_replace('/.*\s/', '', $string);
    }
}
