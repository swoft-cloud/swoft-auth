<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Parser;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\App;
use Swoft\Auth\Constants\AuthConstants;
use Swoft\Auth\Mapping\AuthHandleInterface;
use Swoft\Bean\Annotation\Value;
use Swoft\Helper\ArrayHelper;
use Swoft\Http\Server\Parser\RequestParserInterface;

class AuthorizationHeaderParser implements RequestParserInterface
{
    /**
     * The parsers
     *
     * @var array
     */
    private $authTypes = [];

    /**
     * @Value("${config.auth.types}")
     * @var array
     */
    private $types = [];

    private $headerKey = AuthConstants::HEADER_KEY;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function parse(ServerRequestInterface $request): ServerRequestInterface
    {
        $authValue = $request->getHeaderLine($this->headerKey);
        $type = $this->getHeadString($authValue);
        if (isset($this->mergeTypes()[$type])) {
            /** @var AuthHandleInterface $handler */
            $handler = App::getBean($this->mergeTypes()[$type]);
            $request = $handler->parse($request);
        }
        return $request;
    }

    private function getHeadString(string $val):string
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
            BearerTokenParser::NAME => BearerTokenParser::class,
            BasicAuthParser::NAME => BasicAuthParser::class
        ];
    }
}
