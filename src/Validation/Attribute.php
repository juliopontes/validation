<?php
namespace Validation;

use ArrayAccess;

class Attribute
{
    /** @var string */
    protected $key;

    /** @var string */
    protected $name;

    /**
     * @var array
     */
    protected $inputs = [];

    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * Attribute constructor.
     * @param array $inputs
     */
    public function __construct(array $inputs, array $aliases)
    {
        $this->inputs = $inputs;
        $this->aliases = $aliases;
    }

    /**
     * @param $name
     */
    public function setKey($name)
    {
        $this->key = $name;
        if (strpos($name,'.') !== false) {
            $this->name = str_replace('.', '_', str_replace('.*','', $name));
        } else {
            $this->name = $name;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAliasName()
    {
        return isset($this->aliases[$this->name]) ? $this->aliases[$this->name] : $this->name;
    }

    /**
     * @param null $key
     * @return mixed
     */
    public function getValue($key = null)
    {
        return static::search($this->inputs, $key ?: $this->key, '');
    }

    /**
     * @param array $target
     * @param string $key
     * @param $default
     * @return mixed
     */
    private static function search(array $target, $key, $default)
    {
        $key = is_array($key) ? $key : explode('.', $key);

        while (! is_null($segment = array_shift($key))) {
            if ($segment === '*') {
                if (! is_array($target)) {
                    return $default;
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = static::search($item, $key, $default);
                }

                return in_array('*', $key) ? self::collapse($result) : $result;
            }

            if (static::accessible($target) && static::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return $default;
            }
        }

        return $target;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  ArrayAccess|array  $array
     * @param  string|int  $key
     * @return bool
     */
    private static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @param  mixed  $value
     * @return bool
     */
    private static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }
}
