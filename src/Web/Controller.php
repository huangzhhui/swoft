<?php

namespace Swoft\Web;

use App\beans\Filters\CommonParamsFilter;
use App\beans\Filters\LoginFilter;
use Swoft\Base\RequestContext;
use Swoft\App;
use Swoft\Helper\ResponseHelper;

/**
 * 控制器
 *
 * @uses      Controller
 * @version   2017年11月05日
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Controller extends \Swoft\Base\Controller
{
    use ViewRendererTrait;

    /**
     * @return \Swoft\Web\Request
     */
    public function request(): Request
    {
        return App::getRequest();
    }

    /**
     * @return \Swoft\Web\Response
     */
    public function response(): Response
    {
        return App::getResponse();
    }

    /**
     * json格式输出
     *
     * @param mixed  $data    数据
     * @param string $message 文案
     * @param int    $status  状态，200成功，非200失败
     * @deprecated 非标准输出
     * @return \Swoft\Web\Response
     */
    public function outputJson($data = '', $message = '', $status = 200)
    {
        $data = ResponseHelper::formatData($data, $message, $status);
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        $response = RequestContext::getResponse();
        $response->setFormat(Response::FORMAT_JSON);
        $response->setResponseContent($json);
        return $response;
    }

    /**
     * getRenderer
     *
     * @return ViewRenderer
     */
    public function getRenderer()
    {
        return App::getBean('renderer');
    }

    /**
     * @param string $view
     * @return string
     */
    protected function resolveView(string $view)
    {
        return App::getAlias($view);
    }
}
