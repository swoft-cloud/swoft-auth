<?php declare(strict_types=1);

namespace Swoft\Auth;

use Swoft\Auth\Contract\AuthManagerInterface;
use Swoft\Auth\Contract\AuthorizationParserInterface;
use Swoft\Auth\Contract\AuthServiceInterface;
use Swoft\Auth\Parser\AuthorizationHeaderParser;
use Swoft\Auth\Parser\JWTTokenParser;
use Swoft\Helper\ComposerJSON;
use Swoft\SwoftComponent;
use function dirname;

/**
 * Class AutoLoader
 *
 * @since 2.0
 */
class AutoLoader extends SwoftComponent
{
    /**
     * Get namespace and dirs
     *
     * @return array
     */
    public function getPrefixDirs(): array
    {
        return [
            __NAMESPACE__ => __DIR__,
        ];
    }

    /**
     * @return array
     */
    public function beans(): array
    {
        return [
            AuthorizationParserInterface::class => [
                'class' => AuthorizationHeaderParser::class
            ],
            AuthManagerInterface::class         => [
                'class'            => AuthManager::class,
                'tokenParserClass' => JWTTokenParser::class,
            ],
            AuthServiceInterface::class         => [
                'class' => AuthUserService::class
            ]
        ];
    }

    /**
     * @return array
     */
    public function metadata(): array
    {
        $jsonFile = dirname(__DIR__) . '/composer.json';

        return ComposerJSON::open($jsonFile)->getMetadata();
    }
}
