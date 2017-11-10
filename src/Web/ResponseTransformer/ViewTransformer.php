<?php

namespace Swoft\Web\ResponseTransformer;

use Swoft\App;
use Swoft\Bean\Collector;
use Swoft\Helper\StringHelper;
use Swoft\Web\Response;
use Swoft\Web\ViewRenderer;
use Swoft\Web\ViewRendererTrait;

/**
 * @uses      ViewTransformer
 * @version   2017-11-10
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ViewTransformer extends AbstractTransformer
{

    use ViewRendererTrait;

    /**
     * @return bool
     */
    public function isMatch(): bool
    {
        $isMatch = StringHelper::startsWith($this->getAccept(), 'text/html') === true;
        $template = Collector::$requestMapping[$this->getControllerClass()]['view']['template'] ?? null;
        return $isMatch && $template;
    }

    /**
     * @return \Swoft\Web\Response
     */
    public function transfer(): Response
    {
        $template = Collector::$requestMapping[$this->getControllerClass()]['view']['template'] ?? null;
        $layout = Collector::$requestMapping[$this->getControllerClass()]['view']['layout'] ?? null;
        $response = $this->render($template, $this->getResult(), $layout);
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
}