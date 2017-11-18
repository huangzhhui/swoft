<?php

namespace App\Controllers;

use Swoft\Bean\Annotation\AutoController;
use Swoft\Bean\Annotation\Middleware;
use Swoft\Bean\Annotation\Middlewares;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Web\Controller;


/**
 * @AutoController(prefix="/middleware")
 * 多个 Middleware
 * @Middlewares({
 *     @Middleware(class="\Swoft\Web\Middlewares\GroupTestMiddleware"),
 *     @Middleware(class="Swoft\Web\Middlewares\GroupTestMiddleware"),
 *     @Middleware(class="Swoft\Web\Middlewares\SubMiddlewares")
 * })
 * 单个 Middleware
 * @Middleware(class="Swoft\Web\Middlewares\GroupTestMiddleware")
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
     * @RequestMapping()
     * 多个 Middleware
     * @Middlewares({
     *     @Middleware(class="\Swoft\Web\Middlewares\ActionTestMiddleware"),
     *     @Middleware(class="Swoft\Web\Middlewares\ActionTestMiddleware")
     * })
     * 单个 Middleware
     * @Middleware(class="Swoft\Web\Middlewares\ActionTestMiddleware")
     *
     * @return string
     */
    public function actionTestMiddlewares()
    {
        return 'success';
    }

}