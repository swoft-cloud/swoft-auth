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

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * @Bean(scope=Bean::PROTOTYPE)
 */
class AuthSession
{
    /**
     * @var string User personal information credentials
     */
    protected $identity = '';

    /**
     * @var string Login method name
     */
    protected $accountTypeName = '';

    /**
     * @var string Authentication credentials
     */
    protected $token = '';

    /**
     * @var int Creation time
     */
    protected $createTime = 0;

    /**
     * @var int
     */
    protected $expirationTime = 0;

    /**
     * @var array Expand data, define it yourself
     */
    protected $extendedData = [];

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function setIdentity(string $identity): self
    {
        $this->identity = $identity;
        return $this;
    }

    public function getAccountTypeName(): string
    {
        return $this->accountTypeName;
    }

    public function setAccountTypeName(string $accountTypeName): self
    {
        $this->accountTypeName = $accountTypeName;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getCreateTime(): int
    {
        return $this->createTime;
    }

    public function setCreateTime(int $createTime): self
    {
        $this->createTime = $createTime;
        return $this;
    }

    public function getExpirationTime(): int
    {
        return $this->expirationTime;
    }

    public function setExpirationTime(int $expirationTime): self
    {
        $this->expirationTime = $expirationTime;
        return $this;
    }

    public function getExtendedData(): array
    {
        return (array)$this->extendedData;
    }

    public function setExtendedData(array $extendedData): self
    {
        $this->extendedData = $extendedData;
        return $this;
    }
}
