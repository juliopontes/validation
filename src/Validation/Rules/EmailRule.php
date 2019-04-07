<?php
namespace Validation\Rules;

use Validation\Rule;

class EmailRule extends Rule
{
    /** @var string */
    protected $message = "The :attribute is not valid email";

    /**
     * Check $value is valid
     *
     * @param mixed $value
     * @return bool
     */
    public function check($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}