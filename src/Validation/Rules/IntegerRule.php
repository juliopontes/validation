<?php
namespace Validation\Rules;

use Validation\Rule;

class IntegerRule extends Rule
{
    /** @var string */
    protected $message = "The :attribute must be integer";

    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */
    public function check($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
}