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
class AuthResult
{
    /**
     * @var string
     */
    protected $identity = '';

    /**
     * @var array
     */
    protected $extendedData = [];

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     *
     * @return AuthResult
     */
    public function setIdentity(string $identity): self
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtendedData(): array
    {
        return $this->extendedData;
    }

    /**
     * @param array $extendedData
     *
     * @return AuthResult
     */
    public function setExtendedData(array $extendedData): self
    {
        $this->extendedData = $extendedData;
        return $this;
    }
}
