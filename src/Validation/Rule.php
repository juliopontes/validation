<?php
namespace Validation;

abstract class Rule
{
    /**
     * Rule name
     *
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $fillableParams = [];

    /**
     * @var array
     */
    protected $requiredParams = [];

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * @var bool
     */
    protected $implicit = false;

    /**
     * @return string
     * @throws Errors\RuleException
     */
    public function getName()
    {
        if (empty($this->name))
        {
            $r = null;

            $class_name = basename(str_replace('\\', '/', get_class($this)));
            if (!preg_match('/(.*)Rule/i', $class_name, $r))
            {
                throw new Errors\RuleException('Rule class "'.$class_name.'" is not a valid rule name.', 500);
            }

            $this->name = strtolower($r[1]);
        }

        return $this->name;
    }

    /**
     * Check whether this rule is implicit
     *
     * @return boolean
     */
    public function isImplicit()
    {
        return $this->implicit;
    }

    /**
     * @param $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return substr(strrchr(get_class($this), '\\'), 1);
    }

    /**
     *
     */
    public function setParameters()
    {
        if (!empty($this->fillableParams)) {
            $argv = func_get_args();
            foreach ($this->fillableParams as $paramNumber => $paramName) {
                $this->params[$paramName] = $argv[$paramNumber] ?: null;
            }
        }
    }

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function parameter($name, $default = null)
    {
        return $this->params[$name] ?: $default;
    }

    /**
     * @param Attribute $attribute
     */
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * @return Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        $find = [':attribute', ':value'];
        foreach (array_keys($this->params) as $paramName) {
            $find[] = ':' . $paramName;
        }
        $replace = [
            $this->attribute->getAliasName(),
            $this->attribute->getValue()
        ];

        return str_replace($find,array_merge($replace,$this->params),$this->message);
    }

    /**
     * @throws Errors\MissingRequiredParameterException
     */
    public function checkRequiredParams()
    {
        if (!empty($this->requiredParams)) {
            $requiredParams = array_diff_key(array_flip($this->requiredParams),$this->params);

            if (!empty($requiredParams)) {
                $rule = self::$name;
                $param = implode("', '", $requiredParams);
                $number = count($requiredParams) > 1 ? 'parameters' : 'parameter' ;

                throw new Errors\MissingRequiredParameterException("Missing required {$number} '{$param}' on rule '{$rule}'");
            }
        }
    }

    /**
     * @param $value
     * @return bool
     */
    abstract function check($value);
}