<?php

namespace Swoft\Web\ResponseTransformer;

use Swoft\Base\RequestContext;
use Swoft\Contract\Arrayable;
use Swoft\Web\Response;

/**
 * @uses      StringJsonTransformer
 * @version   2017-11-10
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class ArrayableJsonTransformer extends AbstractTransformer
{

    /**
     * @return bool
     */
    public function isMatch(): bool
    {
        return is_array($this->getResult()) || $this->getResult() instanceof Arrayable;
    }

    /**
     * @return \Swoft\Web\Response
     */
    public function transfer(): Response
    {
        // Accept json, but result is string
        return RequestContext::getResponse()->json($this->getResult());
    }
}