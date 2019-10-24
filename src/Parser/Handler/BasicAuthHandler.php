<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Parser\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\Auth\AuthConst;
use Swoft\Auth\Contract\AuthHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * @Bean()
 */
class BasicAuthHandler implements AuthHandlerInterface
{
    public const NAME = 'Basic';

    /**
     * @param ServerRequestInterface $request
     *
     * @return ServerRequestInterface
     */
    public function handle(ServerRequestInterface $request): ServerRequestInterface
    {
        $authHeader = $request->getHeaderLine(AuthConst::HEADER_KEY);

        if ($basic = $this->parseValue($authHeader)) {
            $request = $request->withAttribute(AuthConst::BASIC_USER_NAME, $this->getUsername($basic))
                               ->withAttribute(AuthConst::BASIC_PASSWORD, $this->getPassword($basic));
        }

        return $request;
    }

    protected function getUsername(array $basic)
    {
        return $basic[0] ?? '';
    }

    protected function getPassword(array $basic)
    {
        return $basic[1] ?? '';
    }

    protected function parseValue($string): array
    {
        if (strpos(trim($string), static::NAME) !== 0) {
            return null;
        }
        $val = preg_replace('/.*\s/', '', $string);
        if (!$val) {
            return null;
        }
        return explode(':', base64_decode($val));
    }
}
