<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\Auth\Contract\AuthServiceInterface;
use Swoft\Auth\Exception\AuthException;
use Swoft\Exception\SwoftException;

/**
 * Class AuthUserService
 *
 * @since 2.0
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
     * @return AuthSession|null
     * @throws SwoftException
     */
    public function getSession(): ?AuthSession
    {
        return context()->get(AuthConst::AUTH_SESSION);
    }

    /**
     * <code>
     * $controller = $this->getHandlerArray($requestHandler)[0];
     * $method = $this->getHandlerArray($requestHandler)[1];
     * $id = $this->getUserIdentity();
     * if ($id) {
     *     return true;
     * }
     * return false;
     * </code>
     *
     * @param string                 $requestHandler
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function auth(string $requestHandler, ServerRequestInterface $request): bool
    {
        throw new AuthException(
            sprintf('AuthUserService::auth() method should be implemented in %s', static::class), ErrorCode::POST_DATA_NOT_PROVIDED);
    }

    /**
     * @param string $handler
     *
     * @return array|null
     */
    protected function getHandlerArray(string $handler): ?array
    {
        $segments = explode('@', trim($handler));
        if (!isset($segments[1])) {
            return null;
        }
        return $segments;
    }
}
