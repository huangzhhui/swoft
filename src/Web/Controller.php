<?php

namespace Swoft\Web;

use Swoft\App;
use Swoft\Base\RequestContext;
use Swoft\Web\ExceptionHandler\AbstractHandler;
use Swoft\Web\ResponseTransformer\AbstractTransformer;
use Swoft\Web\ResponseTransformer\ArrayableJsonTransformer;
use Swoft\Web\ResponseTransformer\RawTransformer;
use Swoft\Web\ResponseTransformer\StringJsonTransformer;
use Swoft\Web\ResponseTransformer\ViewTransformer;

/**
 * Web Controller
 *
 * @uses      Controller
 * @version   2017年11月05日
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class Controller extends \Swoft\Base\Controller
{
    use ViewRendererTrait;

    /**
     * @var array
     */
    protected $defaultTransformers = [
        ViewTransformer::class => 4,
        StringJsonTransformer::class => 3,
        ArrayableJsonTransformer::class => 2,
        RawTransformer::class => 1,
    ];

    protected $defaultExceptionHandlers = [

    ];

    /**
     * @var \SplPriorityQueue
     */
    protected $transformerQueue;

    /**
     * @var \SplPriorityQueue
     */
    protected $exceptionHandlerQueue;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->transformerQueue = new \SplPriorityQueue();
        $this->exceptionHandlerQueue = new \SplPriorityQueue();
    }

    /**
     * @return \Swoft\Web\Request
     */
    public function request(): Request
    {
        return RequestContext::getRequest();
    }

    /**
     * @return Response
     */
    public function response(): Response
    {
        return RequestContext::getResponse();
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

    /**
     * Run action
     *
     * @param string $actionId action ID
     * @param array  $params   action parameters
     * @return Response
     */
    public function run(string $actionId, array $params = []): Response
    {
        if (empty($actionId)) {
            $actionId = $this->defaultAction;
        }
        try {
            // Run the Action of the Controller
            $response = $this->runAction($actionId, $params);
        } catch (\Throwable $t) {
            // Handle by ExceptionHandler
            while ($this->transformerQueue->valid()) {
                $current = $this->transformerQueue->current();
                $instance = new $current();
                if ($instance instanceof AbstractHandler) {
                    $instance->setException($t);
                    if ($instance->isHandle()) {
                        $response = $instance->handle();
                        break;
                    }
                }
                $this->transformerQueue->next();
            }
        } finally {
            if (! $response instanceof \Swoft\Base\Response) {
                // Detect result of controller and transfer to a Standard Response object
                $accpet = $this->request()->getHeader('accept');
                $this->addDefaultTransformer();
                $response = $this->transferResultToResponse($response, current($accpet));
            }
        }
        return $response;
    }

    /**
     * Add framework default transformers to queue
     */
    protected function addDefaultTransformer()
    {
        foreach ($this->defaultTransformers as $transformer => $priority) {
            $this->transformerQueue->insert($transformer, $priority);
        }
    }

    /**
     * Walk the transformer queue to select a suitable Transformer handle
     * the action result, after this, the method will always return a Response
     *
     * @param mixed  $result
     * @param string $accept
     * @return Response
     */
    protected function transferResultToResponse($result, string $accept = '*/*'): Response
    {
        while ($this->transformerQueue->valid()) {
            $current = $this->transformerQueue->current();
            $instance = new $current();
            if ($instance instanceof AbstractTransformer) {
                $instance->setResult($result)->setAccept($accept)->setControllerClass(static::class);
                if ($instance->isMatch()) {
                    $response = $instance->transfer();
                    break;
                }
            }
            $this->transformerQueue->next();
        }
        return $response;
    }

}
