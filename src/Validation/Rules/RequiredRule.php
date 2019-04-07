<?php
namespace Validation\Rules;

use Validation\Rule;

class RequiredRule extends Rule
{
    /**
     * @var string
     */
    protected $message = ':attribute is required!';

    /**
     * @var bool
     */
    protected $implicit = true;

    /**
     * @param $value
     * @return bool
     */
    public function check($value)
    {
        return !empty($value);
    }
}