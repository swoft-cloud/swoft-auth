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
use ReflectionException;
use Swoft;
use Swoft\Auth\AuthConst;
use Swoft\Auth\Contract\AuthHandlerInterface;
use Swoft\Auth\Contract\AuthManagerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Exception\ContainerException;

/**
 * @Bean()
 */
class BearerTokenHandler implements AuthHandlerInterface
{
    public public const NAME = 'Bearer';

    /**
     * @param ServerRequestInterface $request
     *
     * @return ServerRequestInterface
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function handle(ServerRequestInterface $request): ServerRequestInterface
    {
        /** @var AuthManagerInterface $manager */
        $manager = Swoft::getBean(AuthManagerInterface::class);

        if ($token = $this->getToken($request)) {
            $result  = $manager->authenticateToken($token);
            $request = $request->withAttribute(AuthConst::IS_LOGIN, $result);
        }

        return $request;
    }

    protected function getToken(ServerRequestInterface $request)
    {
        $authHeader = $request->getHeaderLine(AuthConst::HEADER_KEY);
        $authQuery  = $request->getQueryParams()['token'] ?? '';

        return $authQuery ?: $this->parseValue($authHeader);
    }

    /**
     * @param string $string
     *
     * @return string|string[]|null
     */
    protected function parseValue(string $string)
    {
        if (strpos(trim($string), self::NAME) !== 0) {
            return null;
        }

        return preg_replace('/.*\s/', '', $string);
    }
}
