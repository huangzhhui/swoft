<?php

namespace App\Controllers;

use App\Models\Logic\IndexLogic;
use Swoft\App;
use Swoft\Base\Coroutine;
use Swoft\Bean\Annotation\Controller;
use Swoft\Bean\Annotation\Inject;
use Swoft\Bean\Annotation\RequestMapping;
use Swoft\Bean\Annotation\RequestMethod;
use Swoft\Bean\Annotation\View;
use Swoft\Task\Task;

/**
 * 控制器demo
 * @Controller(prefix="/demo2")
 *
 * @uses      DemoController
 * @version   2017年08月22日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class DemoController extends \Swoft\Web\Controller
{
    /**
     * 注入逻辑层
     * @Inject()
     *
     * @var IndexLogic
     */
    private $logic;

    /**
     * 定义一个route,支持get和post方式，处理uri=/demo2/index
     * @RequestMapping(route="index", method={RequestMethod::GET, RequestMethod::POST})
     */
    public function actionIndex()
    {
        // 获取所有GET参数
        $get = $this->request()->query();
        // 获取name参数默认值defaultName
        $getName = $this->request()->query('name', 'defaultName');
        // 获取所有POST参数
        $post = $this->request()->post();
        // 获取name参数默认值defaultName
        $postName = $this->request()->post('name', 'defaultName');
        // 获取所有参，包括GET或POST
        $inputs = $this->request()->input();
        // 获取name参数默认值defaultName
        $inputName = $this->request()->input('name', 'defaultName');

        return compact('get', 'getName', 'post', 'postName', 'inputs', 'inputName');
    }

    /**
     * 定义一个route,支持get,以"/"开头的定义，直接是根路径，处理uri=/index2
     * @RequestMapping(route="/index2", method=RequestMethod::GET)
     */
    public function actionIndex2()
    {
        Coroutine::create(function () {
            App::trace("this is child trace" . Coroutine::id());
            Coroutine::create(function () {
                App::trace("this is child child trace" . Coroutine::id());
            });
        });
        return 'success';
    }

    /**
     * 没有使用注解，自动解析注入，默认支持get和post
     */
    public function actionTask()
    {
        $result = Task::deliver('test', 'corTask', ['params1', 'params2'], Task::TYPE_COR);
        $mysql = Task::deliver('test', 'testMysql', [], Task::TYPE_COR);
        $http = Task::deliver('test', 'testHttp', [], Task::TYPE_COR, 20);
        $rpc = Task::deliver('test', 'testRpc', [], Task::TYPE_COR, 5);
        $result1 = Task::deliver('test', 'asyncTask', [], Task::TYPE_ASYNC);
        return [$rpc, $http, $mysql, $result, $result1];
    }

    public function actionIndex6()
    {
        throw new Exception('AAAA');
        //        $a = $b;
        $A = new AAA();
        return ['data6'];
    }

    /**
     * 子协程测试
     */
    public function actionCor()
    {
        // 创建子协程
        Coroutine::create(function () {
            App::error("child cor error msg");
            App::trace("child cor error msg");
        });

        // 当前协程id
        $cid = Coroutine::id();

        // 当前运行上下文ID, 协程环境中，顶层协程ID; 任务中，当前任务taskid; 自定义进程中，当前进程ID(pid)
        $tid = Coroutine::tid();

        return [$cid, $tid];
    }

    /**
     * 国际化测试
     */
    public function actionI18n()
    {
        $data[] = App::t("title", [], 'zh');
        $data[] = App::t("title", [], 'en');
        $data[] = App::t("msg.body", ["stelin", 999], 'en');
        $data[] = App::t("msg.body", ["stelin", 666], 'en');
        return $data;
    }

    /**
     * 视图渲染demo - 没有使用布局文件
     * @RequestMapping()
     * @View(template="demo/view")
     */
    public function actionView()
    {
        $data = [
            'name' => 'Swoft',
            'repo' => 'https://github.com/swoft-cloud/swoft',
            'doc' => 'https://doc.swoft.org/',
            'doc1' => 'https://swoft-cloud.github.io/swoft-doc/',
            'method' => __METHOD__,
        ];
        return $data;
    }

    /**
     * 视图渲染demo - 使用布局文件
     * @RequestMapping()
     * @View(template="demo/content", layout="layouts/default.php")
     */
    public function actionLayout()
    {
        $layout = 'layouts/default.php';
        $data = [
            'name' => 'Swoft',
            'repo' => 'https://github.com/swoft-cloud/swoft',
            'doc' => 'https://doc.swoft.org/',
            'doc1' => 'https://swoft-cloud.github.io/swoft-doc/',
            'method' => __METHOD__,
            'layoutFile' => $layout
        ];
        return $data;
    }

}
