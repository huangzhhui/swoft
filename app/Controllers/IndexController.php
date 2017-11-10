<?php

namespace App\Controllers;

use Swoft\Bean\Annotation\AutoController;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Bean\Annotation\View;
use Swoft\Web\Controller;

/**
 * Class IndexController
 * @AutoController()
 *
 * @package App\Controllers
 */
class IndexController extends Controller
{

    /**
     * @RequestMapping()
     * @View(template="index/index")
     */
    public function actionIndex()
    {
        $name = 'Swoft';
        $notes = ['New Generation of PHP Framework', 'Hign Performance, Coroutine and Full Stack'];
        // throw new \RuntimeException('test');
        return compact('name', 'notes');
    }

}
