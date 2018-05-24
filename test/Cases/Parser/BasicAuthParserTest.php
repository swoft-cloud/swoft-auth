<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace SwoftTest\Auth\Parser;

use SwoftTest\Auth\AbstractTestCase;

class BasicAuthParserTest extends AbstractTestCase
{

    public function testParser(){
        $request = $this->raw("get","test",[],['Authorization'=>'Basic 1'],"test");
        var_dump($request);
    }

}
