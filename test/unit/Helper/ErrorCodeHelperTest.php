<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Auth\UnitHelper;

use Swoft\Auth\ErrorCode;
use Swoft\Auth\ErrorCodeHelper;
use SwoftTest\Auth\Unit\AbstractTestCase;

class ErrorCodeHelperTest extends AbstractTestCase
{
    /**
     * @covers ErrorCodeHelper::get()
     */
    public function testGet(): void
    {
        $helper = new ErrorCodeHelper();
        $arr    = $helper->get(ErrorCode::ACCESS_DENIED);
        $this->assertArrayHasKey('statusCode', $arr);
    }
}
