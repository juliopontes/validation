<?php
namespace Validation;

use ReflectionClass;
use ReflectionException;

abstract class Validator
{
    /**
     * @var array
     */
    protected static $validators = [];

    /**
     * Given $ruleName and $rule to add new validator
     *
     * @param string $ruleName
     * @param Rule $rule
     * @return void
     */
    public static function addValidator($ruleName, Rule &$rule)
    {
        self::$validators[$ruleName] = $rule;
    }

    /**
     * @param $name
     * @return Rule
     * @throws Errors\RuleNotFoundException
     */
    public static function getValidator($name)
    {
        if (!isset(self::$validators[$name])) {
            throw new Errors\RuleNotFoundException(sprintf('Rule %s not found',$name), 2);
        }

        return self::$validators[$name];
    }

    /**
     * @param $path
     * @throws Errors\RuleException
     * @throws ReflectionException
     */
    public static function validatorsInPath($path)
    {
        if (is_dir($path)) {
            foreach (glob($path . '/*Rule.php') as $rule_file) {
                $class_name = __NAMESPACE__.'\\Rules\\' . basename($rule_file,'.php');

                if (!class_exists($class_name)) {
                    include_once $rule_file;
                }

                if (class_exists($class_name)) {
                    $class = new ReflectionClass($class_name);
                    if (!$class->isSubclassOf(Rule::class)) {
                        throw new Errors\RuleException(sprintf('Rule %s must extend '.Rule::class, $class->getShortName()));
                    }
                    $instance = $class->newInstance();

                    self::addValidator($instance->getName(), $instance);
                }
            }
        }
    }

    /**
     * @throws ReflectionException
     * @throws Errors\RuleException
     */
    protected static function registerBaseValidators()
    {
        self::validatorsInPath(__DIR__.'/Rules');
    }

    /**
     * @return array
     */
    public static function getValidators()
    {
        return self::$validators;
    }

    /**
     * @param array $inputs
     * @param array $rules
     * @param array $messages
     * @param array $aliases
     * @return Validation
     */
    public static function make(array $inputs, array $rules, array $messages = [], array $aliases = [])
    {
        self::registerBaseValidators();
        return new Validation($inputs, $rules, $messages, $aliases);
    }
}