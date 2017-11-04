<?php

namespace Swoft\Web;

/**
 * Trait implementing functionality common to requests and responses.
 */
trait MessageTrait
{

    /**
     * Trims whitespace from the header values.
     * Spaces and tabs ought to be excluded by parsers when extracting the field value from a header field.
     * header-field = field-name ":" OWS field-value OWS
     * OWS          = *( SP / HTAB )
     *
     * @param string[] $values Header values
     * @return string[] Trimmed header values
     * @see https://tools.ietf.org/html/rfc7230#section-3.2.4
     */
    private function trimHeaderValues(array $values)
    {
        return array_map(function ($value) {
            return trim($value, " \t");
        }, $values);
    }
}
