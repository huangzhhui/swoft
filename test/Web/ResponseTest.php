<?php

namespace Swoft\Test\Response;

use Swoft\Test\AbstractTestCase;
use Swoft\Web\Response;

/**
 * @uses      ResponseTest
 * @version   2017-11-11
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ResponseTest extends AbstractTestCase
{


    public function testJsonFormat()
    {
        $swooleResponse = new \Swoole\Http\Response();
        $response = new Response($swooleResponse);
        $response = $response->json(['key' => 'value']);
        $contentType = $response->getHeader('Content-Type');
        echo '<pre>';var_dump($contentType);echo '</pre>';exit();
    }
}