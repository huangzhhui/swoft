<?php

namespace Swoft\Web;

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
    const FORMAT_HTML = 'html';
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    /**
     * @var int
     */
    protected $status = 200;

    /**
     * @var string
     */
    protected $charset = "utf-8";

    /**
     * @var string
     */
    protected $responseContent = "";

    /**
     * @var string
     */
    protected $format = self::FORMAT_HTML;

    /**
     * @var \Throwable 未知异常
     */
    private $exception = null;

    /**
     * 输出contentTypes集合
     *
     * @var array
     */
    private $contentTypes = [
        self::FORMAT_XML => 'text/xml',
        self::FORMAT_HTML => 'text/html',
        self::FORMAT_JSON => 'application/json',
    ];

    /**
     * 重定向
     *
     * @param string   $url
     * @param null|int $status
     * @return mixed
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
     * Json 响应
     *
     * @param  mixed $data            The data
     * @param  int   $status          The HTTP status code.
     * @param  int   $encodingOptions Json encoding options
     * @throws \RuntimeException
     * @return static
     */
    public function json($data, $status = null, $encodingOptions = 0)
    {
        $this->swooleResponse->write($json = json_encode($data, $encodingOptions));

        // Ensure that the json encoding passed successfully
        if ($json === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        $this->swooleResponse->header('Content-Type', 'application/json;charset=utf-8');

        if (null !== $status) {
            $this->swooleResponse->status((int)$status);
        }

        return $this;
    }

    /**
     * 显示数据
     */
    public function send()
    {
        $response = $this;

        /**
         * Headers
         */
        // Handle Content-Type
        $response = $response->withAddedHeader('Content-Type', $this->contentTypes[$this->format]);
        $response = $response->withAddedHeader('Content-Type', 'charset=' . $this->charset);
        // Write Headers to swoole response
        $headers = $response->getHeaders();
        foreach ($headers as $key => $value) {
            $this->swooleResponse->header($key, implode(';', $value));
        }

        /**
         * Cookies
         */
        // TODO: handle cookies

        /**
         * Status Code
         */
        $response = $response->withStatus($this->getStatusCode());
        $this->swooleResponse->status($response->getStatusCode());

        /**
         * Body
         */
        $response = $response->withBody(new SwooleStream($this->responseContent));
        $this->swooleResponse->end($response->getBody()->getContents());
    }

    /**
     * 添加header
     *
     * @param string $name
     * @param string $value
     */
    public function addHeader(string $name, string $value)
    {
        $this->swooleResponse->header($name, $value);
    }

    /**
     * 设置Http code
     *
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * 设置格式json/html/xml...
     *
     * @param string $format
     * @return $this
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * charset设置
     *
     * @param string $charset
     * @return $this
     */
    public function setCharset(string $charset)
    {
        $this->charset = $charset;
        return $this;
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
     * @return $this
     */
    public function setException(\Throwable $exception)
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * 设置返回内容
     *
     * @param string $responseContent
     * @return $this
     */
    public function setResponseContent(string $responseContent)
    {
        $this->responseContent = $responseContent;
        return $this;
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
