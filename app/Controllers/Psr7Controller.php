<?php

namespace App\Controllers;

use Psr\Http\Message\UploadedFileInterface;
use Swoft\Bean\Annotation\AutoController;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Bean\Annotation\View;
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
        $param1 = $this->request()->query('param1');
        $param2 = $this->request()->query('param2', 'defaultValue');
        return compact('param1', 'param2');
    }

    /**
     * @RequestMapping()
     */
    public function actionPost()
    {
        $param1 = $this->request()->post('param1');
        $param2 = $this->request()->post('param2');
        return compact('param1', 'param2');
    }

    /**
     * @RequestMapping()
     */
    public function actionInput()
    {
        $param1 = $this->request()->input('param1');
        $param2 = $this->request()->input();
        return compact('param1', 'param2');
    }

    /**
     * @RequestMapping()
     */
    public function actionRaw()
    {
        $param1 = $this->request()->raw();
        return compact('param1');
    }

    /**
     * @RequestMapping()
     * @return \Swoft\Web\Response
     */
    public function actionCookies()
    {
        $cookie1 = $this->request()->cookie();
        return compact('cookie1');
    }

    /**
     * @RequestMapping()
     * @return \Swoft\Web\Response
     */
    public function actionHeader()
    {
        $header1 = $this->request()->header();
        $header2 = $this->request()->header('host');
        return compact('header1', 'header2');
    }

    /**
     * @RequestMapping()
     * @return \Swoft\Web\Response
     */
    public function actionJson()
    {
        $json = $this->request()->json();
        $jsonParam = $this->request()->json('jsonParam');
        return compact('json', 'jsonParam');
    }

    /**
     * @RequestMapping()
     * @return \Swoft\Web\Response
     */
    public function actionFiles()
    {
        $files = $this->request()->file();
        foreach ($files as $file) {
            if ($file instanceof UploadedFileInterface) {
                try {
                    $file->moveTo('@runtime/uploadfiles/1.png');
                    $move = true;
                } catch (\Throwable $e) {
                    $move = false;
                }
            }
        }

        return compact('move');
    }

    /**
     * @RequestMapping()
     * @View(template="psr7/index", layout="xxx")
     */
    public function actionIndex()
    {
        $data = [
            'key1' => 'value1',
            'key2' => 2,
        ];
        $throwException = false;
        if ($throwException) {
            // 异常情况由 ExceptionHandler 处理
            throw new \RuntimeException();
        }
        // Data 根据 Request 中的 Accept 返回对应的格式
        // 正常情况返回 200 状态码，其余状态码由 ExceptionHandler 处理
        return $data;
    }

}