<?php

namespace Swoft\Web;

use Swoft\Contract\Arrayable;
use Swoft\Helper\JsonHelper;

/**
 * 响应response
 *
 * @uses      Response
 * @version   2017年05月11日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Response extends \Swoft\Base\Response
{

    /**
     * @var \Throwable 未知异常
     */
    private $exception = null;

    /**
     * 重定向
     *
     * @param string   $url
     * @param null|int $status
     * @return static
     */
    public function redirect($url, $status = null)
    {
        $this->swooleResponse->header('Location', (string)$url);

        if (null === $status) {
            $status = 302;
        }

        if (null !== $status) {
            $this->swooleResponse->status((int)$status);
        }

        return $this;
    }

    /**
     * Raw 响应
     *
     * @param  mixed $data   The data
     * @param  int   $status The HTTP status code.
     * @param string $charset
     * @return \Swoft\Web\Response when $data not jsonable
     */
    public function raw($data, int $status = 200, $charset = 'utf-8'): Response
    {
        $response = $this;
        return $response->withAddedHeader('Content-Type', 'text/plain')
                        ->withAddedHeader('Content-Type', sprintf('charset=%s', $charset))
                        ->withContent($data)
                        ->withStatus($status);
    }

    /**
     * Json 响应
     *
     * @param  mixed $data            The data
     * @param  int   $status          The HTTP status code.
     * @param  int   $encodingOptions Json encoding options
     * @param string $charset
     * @return static when $data not jsonable
     */
    public function json($data, int $status = 200, int $encodingOptions = 0, $charset = 'utf-8'): Response
    {
        if (! is_array($data) && ! is_string($data) && ! $data instanceof Arrayable) {
            throw new \InvalidArgumentException('Invalid data provided');
        }
        is_string($data) && $data = ['data' => $data];

        $response = $this;

        // Headers
        $response = $response->withAddedHeader('Content-Type', 'application/json')
                             ->withAddedHeader('Content-Type', sprintf('charset=%s', $charset));

        // Content and Status code
        $content = JsonHelper::encode($data, $encodingOptions);
        $response = $response->withContent($content)->withStatus($status);

        return $response;
    }

    /**
     * 处理 Response 并发送数据
     */
    public function send(): void
    {
        $response = $this;

        /**
         * Headers
         */
        // Write Headers to swoole response
        foreach ($response->getHeaders() as $key => $value) {
            $this->swooleResponse->header($key, implode(';', $value));
        }

        /**
         * Cookies
         */
        // TODO: handle cookies

        /**
         * Body
         */
        $this->swooleResponse->end($response->getBody()->getContents());
    }

    /**
     * 获取异常
     *
     * @return \Throwable 异常
     */
    public function getException(): \Throwable
    {
        return $this->exception;
    }

    /**
     * 设置异常
     *
     * @param \Throwable $exception 初始化异常
     * @return static
     */
    public function setException(\Throwable $exception)
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * 设置Body内容，使用默认的Stream
     *
     * @param string $content
     * @return static
     */
    public function withContent($content)
    {
        if ($this->stream) {
            return $this;
        }

        $new = clone $this;
        $new->stream = new SwooleStream($content);
        return $new;
    }

    /**
     * 添加cookie
     *
     * @param string  $key
     * @param  string $value
     * @param int     $expire
     * @param string  $path
     * @param string  $domain
     */
    public function addCookie($key, $value, $expire = 0, $path = '/', $domain = '')
    {
        $this->swooleResponse->cookie($key, $value, $expire, $path, $domain);
    }

}
