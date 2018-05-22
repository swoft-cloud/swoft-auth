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
            ServiceConstants::AUTH_REQUEST_HEADER_PARSER => [
                'class' => AuthorizationHeaderParser::class
            ],
            ServiceConstants::AUTH_MANAGER=>[
                'class' => AuthManager::class,
                'tokenParserClass'=>JWTTokenParser::class,
            ],
            ServiceConstants::AUTH_USERS_SERVICE=>[
                'class'=>AuthUserService::class
            ]
        ];
    }
}
