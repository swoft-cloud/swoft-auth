<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/4/18
 * Time: 下午3:49
 */

namespace Swoft\Auth\Bean;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Scope;

/**
 * Class AuthResult
 * @package Swoft\Auth\Bean
 * @Bean(scope=Scope::PROTOTYPE)
 */
class AuthResult
{
    /**
     * @var string
     */
    protected $identity='';
    /**
     * @var array
     */
    protected $extendedData=[];

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     * @return AuthResult
     */
    public function setIdentity(string $identity)
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
     * @return AuthResult
     */
    public function setExtendedData(array $extendedData)
    {
        $this->extendedData = $extendedData;
        return $this;
    }


}