<?php

namespace Swoft\Web\ExceptionHandler;

use Swoft\Base\RequestContext;
use Swoft\Base\Response;

/**
 * @uses      AbstractHandler
 * @version   2017-11-10
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractHandler
{

    /**
     * Response status code
     *
     * @var int
     */
    protected $statusCode = 500;

    /**
     * Exception message
     *
     * @var string
     */
    protected $message = 'System error';

    /**
     * @var \Throwable
     */
    protected $exception;

    /**
     * This handler should handle the exception ?
     *
     * @return bool
     */
    abstract public function isHandle(): bool;

    /**
     * handle the exception and return a Response
     *
     * @return \Swoft\Base\Response|string|array|mixed
     */
    abstract public function handle();

    /**
     * Set the response body structure
     *
     * @return array
     */
    protected function handleBody()
    {
        return [
            'message' => $this->getMessage(),
        ];
    }

    /**
     * Transfer current instance to a Response
     *
     * @return \Swoft\Base\Response
     */
    protected function toResponse()
    {
        return RequestContext::getResponse()->withContent($this->handleBody())->withStatus($this->getStatusCode());
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return static
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return static
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return \Throwable
     */
    public function getException(): \Throwable
    {
        return $this->exception;
    }

    /**
     * @param \Throwable $exception
     * @return static
     */
    public function setException(\Throwable $exception): self
    {
        $this->exception = $exception;
        return $this;
    }

}