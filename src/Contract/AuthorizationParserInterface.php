<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Auth\Contract;

use Psr\Http\Message\ServerRequestInterface;

interface AuthorizationParserInterface
{
    public function parse(ServerRequestInterface $request): ServerRequestInterface;
}
