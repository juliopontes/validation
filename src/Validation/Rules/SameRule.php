<?php
namespace Validation\Rules;

use Validation\Rule;

class SameRule extends Rule
{
    /** @var string */
    protected $message = "The :attribute must be same with :field";

    /** @var array */
    protected $fillableParams = ['field'];

    /**
     * @param $value
     * @return bool
     */
    public function check($value)
    {
        return $value == $this->getAttribute()->getValue($this->parameter('field'));
    }
}