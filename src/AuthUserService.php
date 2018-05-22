<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\Auth\Bean\AuthSession;
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Auth\Mapping\AuthServiceInterface;
use Swoft\Core\RequestContext;

/**
 * Class AuthUserService
 * @package Swoft\Auth
 */
class AuthUserService implements AuthServiceInterface
{
    public function getUserIdentity(): string
    {
        if (!$this->getSession()) {
            return '';
        }
        return $this->getSession()->getIdentity() ?? '';
    }

    public function getUserExtendData(): array
    {
        if (!$this->getSession()) {
            return [];
        }
        return $this->getSession()->getExtendedData() ?? [];
    }

    /**
     * @return AuthSession |null
     */
    public function getSession()
    {
        return RequestContext::getContextDataByKey(AuthConstants::AUTH_SESSION) ?? null;
    }

    /**
     * <code>
     * $controller = $this->getHandlerArray($requestHandler)[0];
     * $method = $this->getHandlerArray($requestHandler)[1];
     * $id = $this->getUserIdentity();
     * if ($id) {
     * return true;
     * }
     * return false;
     * </code>
     *
     * @param string $requestHandler
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function auth(string $requestHandler, ServerRequestInterface $request): bool
    {
        echo sprintf(" 你访问了: %s",$requestHandler);
        return true;
    }

    /**
     * @param string $handler
     * @return array|null
     */
    protected function getHandlerArray(string $handler)
    {
        $segments = explode('@', trim($handler));
        if (!isset($segments[1])) {
            return null;
        }
        return $segments;
    }

}
