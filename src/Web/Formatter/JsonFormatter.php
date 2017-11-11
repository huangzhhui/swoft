<?php

namespace Swoft\Web\ResponseTransformer;

use Swoft\Contract\Arrayable;
use Swoft\Helper\JsonHelper;
use Swoft\Web\Response;

/**
 * @uses      JsonFormatter
 * @version   2017-11-11
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class JsonFormatter extends AbstractFormatter
{

    /**
     * @return bool
     */
    public function isMatch(): bool
    {
        $result = $this->getResult();
        return is_string($result) || is_array($result) || $result instanceof Arrayable;
    }

    /**
     * @return \Swoft\Web\Response
     */
    public function format(): Response
    {
        $result = $this->getResult();
        if (is_array($result)) {
            $formattedResult = $result;
        } elseif ($result instanceof Arrayable) {
            $formattedResult = $result->toArray();
        } else {
            $formattedResult = [
                'data' => $result,
            ];
        }
        $response = $this->getResponse();
        // Headers
        $response = $response->withAddedHeader('Content-Type', 'application/json')
                             ->withAddedHeader('Content-Type', sprintf('charset=%s', $charset));

        // Content and Status code
        $content = JsonHelper::encode($formattedResult, $encodingOptions);
        $response = $response->withContent($content);
    }
}