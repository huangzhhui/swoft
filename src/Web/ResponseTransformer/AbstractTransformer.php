<?php

namespace Swoft\Web\ResponseTransformer;

use Swoft\Web\Response;

/**
 * @uses      AbstractTransformer
 * @version   2017-11-10
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
abstract class AbstractTransformer
{

    /**
     * @var string
     */
    protected $controllerClass;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * @var string
     */
    protected $accept;

    /**
     * @return bool
     */
    abstract public function isMatch(): bool;

    /**
     * @return \Swoft\Web\Response
     */
    abstract public function transfer(): Response;

    /**
     * @return string
     */
    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    /**
     * @param string $controllerClass
     * @return static
     */
    public function setControllerClass(string $controllerClass): self
    {
        $this->controllerClass = $controllerClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     * @return static
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccept(): string
    {
        return $this->accept;
    }

    /**
     * @param string $accept
     * @return static
     */
    public function setAccept(string $accept): self
    {
        $this->accept = $accept;
        return $this;
    }
}