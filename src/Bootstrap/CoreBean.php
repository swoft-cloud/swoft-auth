<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2018/5/20
 * Time: 下午4:32
 * @author April2 <ott321@yeah.net>
 */

namespace Swoft\Auth\Bootstrap;

use Swoft\Auth\AuthManager;
use Swoft\Auth\AuthUserService;
use Swoft\Auth\Constants\ServiceConstants;
use Swoft\Auth\Helper\ErrorCodeHelper;
use Swoft\Auth\Parser\AuthorizationHeaderParser;
use Swoft\Auth\Parser\RequestHeaderParser;
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
            ServiceConstants::AUTH_REQUEST_HEADER_PARSER=>AuthorizationHeaderParser::class,
            ServiceConstants::AUTH_MANAGER=>AuthManager::class,
            ServiceConstants::AUTH_ERROR_CODE_HELPER=>ErrorCodeHelper::class,
            ServiceConstants::AUTH_USERS_SERVICE=>AuthUserService::class
        ];
    }
}