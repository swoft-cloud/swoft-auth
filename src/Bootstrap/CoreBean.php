<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Bootstrap;

use Swoft\Auth\AuthManager;
use Swoft\Auth\AuthUserService;
use Swoft\Auth\Constants\ServiceConstants;
use Swoft\Auth\Parser\AuthorizationHeaderParser;
use Swoft\Auth\Parser\JWTTokenParser;
use Swoft\Bean\Annotation\BootBean;
use Swoft\Core\BootBeanInterface;

/**
 * Class CoreBean
 * @package Swoft\Auth\Bootstrap
 * @BootBean()
 */
class CoreBean implements BootBeanInterface
{
    /**
     * @return array
     */
    public function beans()
    {
        return [
            AuthorizationHeaderParser::class=> [
                'class' => AuthorizationHeaderParser::class
            ],
            AuthManager::class=>[
                'class' => AuthManager::class,
                'tokenParserClass'=>JWTTokenParser::class,
            ],
            AuthUserService::class=>[
                'class'=>AuthUserService::class
            ]
        ];
    }
}
