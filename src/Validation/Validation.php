<?php
namespace Validation;

class Validation
{
    /**
     * State Check
     *
     * @var bool
     */
    protected $ran = false;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var array
     */
    protected $validatedData = [];

    /**
     * Validation constructor.
     * @param array $rules
     * @param array $inputs
     * @param array $schema
     */
    public function __construct(array $inputs, array $schema, array $messages = [], array $aliases = [])
    {
        $this->input = $inputs;
        $this->schema = $schema;
        $this->messages = $messages;
        $this->aliases = $aliases;

        $this->validate();
    }

    /**
     * @throws Errors\MissingRequiredParameterException
     * @throws Errors\RuleException
     * @throws Errors\RuleNotFoundException
     */
    protected function validate()
    {
        $this->validatedData = [];
        $this->errors = [];
        if (!empty($this->input)) {
            $attribute = new Attribute($this->input, $this->aliases);

            foreach ($this->schema as $key => $ruleString) {
                $attribute->setKey($key);

                $valid = true;
                foreach (explode('|',$ruleString) as $k => $ruleValidator) {
                    $ruleParts = explode(':', $ruleValidator, 2);
                    $ruleName = array_shift($ruleParts);
                    $ruleArgs = !empty($ruleParts) ? explode(',', array_shift($ruleParts)) : [];

                    $ruleInstance = Validator::getValidator($ruleName);

                    $ruleInstance->setAttribute($attribute);


                    $message = null;
                    foreach ([$attribute->getName() . '.' . $ruleInstance->getName(),$ruleInstance->getName()] as $id) {
                        if (isset($this->messages[$id])) {
                            $message = $this->messages[$id];
                            break;
                        }
                    }
                    if (!empty($message)) {
                        $ruleInstance->setMessage($message);
                    }

                    if (!empty($ruleArgs)) {
                        call_user_func_array([$ruleInstance,'setParameters'],$ruleArgs);
                    }

                    $ruleInstance->checkRequiredParams();
                    $valid = $ruleInstance->check($attribute->getValue());

                    if (!$valid) {
                        $message = $ruleInstance->getMessage();
                        if (!empty($message)) {
                            $this->errors[ $attribute->getName() ][] = $message;
                        }
                        if ($ruleInstance->isImplicit()) {
                            break;
                        }
                    }
                }

                if (!isset($this->errors[$attribute->getName()]) && $valid) {
                    $this->validatedData[$attribute->getName()] = $attribute->getValue();
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getValidData()
    {
        return $this->validatedData;
    }

    /**
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Check validation success
     *
     * @return bool
     */
    public function success()
    {
        return empty($this->errors);
    }

    /**
     * Check validation fails
     *
     * @return bool
     */
    public function fails()
    {
        return !empty($this->errors);
    }
}