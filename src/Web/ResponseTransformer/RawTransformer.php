<?php

namespace Swoft\Web\ResponseTransformer;

use Swoft\Base\RequestContext;
use Swoft\Web\Response;

/**
 * @uses      RawTransformer
 * @version   2017-11-10
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RawTransformer extends AbstractTransformer
{

    /**
     * @return bool
     */
    public function isMatch(): bool
    {
        return true;
    }

    /**
     * @return \Swoft\Web\Response
     */
    public function transfer(): Response
    {
        return RequestContext::getResponse()->raw($this->getResult());
    }
}