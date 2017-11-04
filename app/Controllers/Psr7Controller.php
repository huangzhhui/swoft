<?php

namespace App\Controllers;

use Swoft\Bean\Annotation\AutoController;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Web\Controller;

/**
 * @AutoController(prefix="/psr7")
 * @uses      Psr7Controller
 * @version   2017-11-05
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Psr7Controller extends Controller
{

    /**
     * @RequestMapping()
     */
    public function actionGet()
    {
        $param1 = $this->get('param1');
        $param2 = $this->get('param2');
        return $this->outputJson(compact('param1', 'param2'));
    }

    /**
     * @RequestMapping()
     */
    public function actionPost()
    {
        $param1 = $this->post('param1');
        $param2 = $this->post('param2');
        return $this->outputJson(compact('param1', 'param2'));
    }

}