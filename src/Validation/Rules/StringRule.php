<?php
namespace Validation\Rules;

use Validation\Rule;

class StringRule extends Rule
{
    /**
     * @var string
     */
    protected $message = ':attribute must be string.';

    /**
     * @inheritDoc
     */
    function check($value)
    {
        return is_string($value);
    }
}