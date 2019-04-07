<?php
namespace Validation\Rules;

use Validation\Rule;

class MinRule extends Rule
{
    use Traits\SizeTrait;

    /**
     * @var StringRule
     */
    protected $message = 'The :attribute must be at least :min characters.';

    /** @var array */
    protected $fillableParams = ['min'];

    /**
     * @param $value
     * @return bool
     */
    public function check($value)
    {
        return $this->getValueSize($value) >= $this->parameter('min');
    }
}
