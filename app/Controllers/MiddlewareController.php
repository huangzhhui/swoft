<?php

namespace App\Controllers;

use Swoft\Bean\Annotation\AutoController;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\Middleware;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Pipeline\Pipeline;
use Swoft\Web\Controller;


/**
 * @AutoController(prefix="/middleware")
 *
 * @uses      MiddlewareController
 * @version   2017年11月14日
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class MiddlewareController extends Controller
{

    /**
     * @Inject()
     * @var \Swoft\Web\Dispatcher
     */
    protected $dispatcher;

    /**
     * @RequestMapping()
     * @Middleware(class="\Swoft\Web\Middlewares\TestMiddleware")
     */
    public function actionTest()
    {
        return 'success';
    }

}