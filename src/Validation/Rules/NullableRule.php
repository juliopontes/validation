<?php
namespace Validation\Rules;

use Validation\Rule;

class NullableRule extends Rule
{
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
