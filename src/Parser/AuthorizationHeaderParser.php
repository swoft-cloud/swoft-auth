<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Parser;

use Psr\Http\Message\ServerRequestInterface;
use Swoft;
use Swoft\Auth\AuthConst;
use Swoft\Auth\Contract\AuthHandlerInterface;
use Swoft\Auth\Contract\AuthorizationParserInterface;
use Swoft\Auth\ErrorCode;
use Swoft\Auth\Exception\AuthException;
use Swoft\Auth\Parser\Handler\BasicAuthHandler;
use Swoft\Auth\Parser\Handler\BearerTokenHandler;
use Swoft\Stdlib\Helper\ArrayHelper;

/**
 * Class AuthorizationHeaderParser
 *
 * @since 2.0
 */
class AuthorizationHeaderParser implements AuthorizationParserInterface
{
    /**
     * @var array
     */
    private $types = [];

    /**
     * The parsers
     *
     * @var array
     */
    private $authTypes = [];

    /**
     * @var string
     */
    private $headerKey = AuthConst::HEADER_KEY;

    /**
     * @param ServerRequestInterface $request
     *
     * @return ServerRequestInterface
     */
    public function parse(ServerRequestInterface $request): ServerRequestInterface
    {
        $authValue = $request->getHeaderLine($this->headerKey);

        $type = $this->getHeadString($authValue);
        if (isset($this->mergeTypes()[$type])) {
            $handler = Swoft::getBean($this->mergeTypes()[$type]);

            if (!$handler instanceof AuthHandlerInterface) {
                throw new AuthException(
                    sprintf('%s  should implement Swoft\Auth\Contract\AuthHandlerInterface',
                        $this->mergeTypes()[$type]), ErrorCode::POST_DATA_NOT_PROVIDED);
            }

            $request = $handler->handle($request);
        }

        return $request;
    }

    private function getHeadString(string $val): string
    {
        return explode(' ', $val)[0] ?? '';
    }

    private function mergeTypes(): array
    {
        if (empty($this->authTypes)) {
            $this->authTypes = ArrayHelper::merge($this->types, $this->defaultTypes());
        }
        return $this->authTypes;
    }

    public function defaultTypes(): array
    {
        return [
            BearerTokenHandler::NAME => BearerTokenHandler::class,
            BasicAuthHandler::NAME   => BasicAuthHandler::class
        ];
    }
}
