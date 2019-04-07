<?php
namespace Validation\Rules;

use Validation\Rule;

class MaxRule extends Rule
{
    use Traits\SizeTrait;

    /**
     * @var string
     */
    protected $message = 'The :attribute maximum is :max';

    /** @var array */
    protected $fillableParams = ['max'];

    /**
     * @param $value
     * @return bool
     */
    public function check($value)
    {
        $valueSize = $this->getValueSize($value);

        if (!is_numeric($valueSize)) {
            return false;
        }

        $max = $this->getBytesSize($this->parameter('max'));

        return $valueSize <= $max;
    }
}
