<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Parser;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\App;
use Swoft\Auth\AuthManager;
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Auth\Exception\AuthException;
use Swoft\Auth\Helper\ErrorCode;
use Swoft\Auth\Mapping\AuthHandleInterface;
use Swoft\Auth\Mapping\AuthManagerInterface;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;

/**
 * Class BearerTokenParser
 * @package Swoft\Auth\Parser
 * @Bean()
 */
class BearerTokenParser implements AuthHandleInterface
{
    const NAME = 'Bearer';

    /**
     * @Value("${config.auth.manager}")
     * @var string
     */
    private $managerClass = AuthManager::class;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function parse(ServerRequestInterface $request): ServerRequestInterface
    {
        $token = $this->getToken($request);
        if (!App::hasBean($this->managerClass)) {
            $error = sprintf('can`t find  %s', $this->managerClass);
            throw new AuthException(ErrorCode::POST_DATA_INVALID, $error);
        }
        /** @var AuthManagerInterface $manager */
        $manager = App::getBean($this->managerClass);
        if ($token) {
            $res = $manager->authenticateToken($token);
            $request = $request->withAttribute(AuthConstants::IS_LOGIN, $res);
        }
        return $request;
    }

    protected function getToken(ServerRequestInterface $request)
    {
        $authHeader = $request->getHeaderLine(AuthConstants::HEADER_KEY) ?? '';
        $authQuery = $request->getQueryParams()['token'] ?? '';
        return $authQuery ? $authQuery : $this->parseValue($authHeader);
    }

    protected function parseValue($string)
    {
        if (strpos(trim($string), self::NAME) !== 0) {
            return null;
        }
        return preg_replace('/.*\s/', '', $string);
    }
}
