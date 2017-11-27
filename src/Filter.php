<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Filter;

use ReflectionClass;
use ReflectionMethod;

/**
 * Filter.
 */
class Filter
{
    /**
     * @var array User data.
     */
    private $data = [];

    /**
     * @var array Error messages.
     */
    private $messages = [];

    /**
     * @var int Occurred errors.
     */
    private $errors = 0;

    /**
     * Class constructor.
     *
     * @param array $rules
     * @param array $data
     */
    public function __construct(array $rules, array $data)
    {
        //$this->rules = $rules;
        $this->data = $data;

        $this->interpreteRules($rules);
    }

    /**
     * Return occurred error number.
     *
     * @return int
     */
    public function getErrors(): int
    {
        return $this->errors;
    }

    /**
     * Return error messages.
     *
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Return passed data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get parsed rules.
     */
    private function interpreteRules(&$rules)
    {
        foreach ($rules as $rule) {
            $this->ruleToField((new RuleInterpreter($rule))->get());
        }
    }

    /**
     * Apply rules to a field.
     *
     * @param array $rules
     */
    private function ruleToField(array $rules)
    {
        foreach ($rules as $rule) {
            $field = $rule[0];
            $filter = $rule[2][0];

            $class = 'Linna\Filter\Rules\\' . $filter;
            $refClass = new ReflectionClass($class);
            $refMethod = new ReflectionMethod($class, 'validate');
                    
            $instance = $refClass->newInstance();

            if (!isset($this->data[$field])) {
                $this->errors++;
                $this->messages[$field][$filter] = "{$field} field missing";
                continue;
            }

            if ($refMethod->invokeArgs($instance, $this->getArguments($rule[2][2], $rule[3], $this->data[$field]))) {
                $this->errors++;
                $this->messages[$field][$filter] = ['expected' => $rule[3], 'received' => $this->data[$field]];
                continue;
            }

            if ($refClass->hasMethod('sanitize')) {
                $instance->sanitize($this->data[$field]);
            }
        }
    }

    /**
     * Return arguments for validation.
     *
     * @param int $args
     * @param mixed $expected
     * @param mixed $received
     *
     * @return array
     */
    private function getArguments(int $args, $expected, $received): array
    {
        if ($args === 0) {
            return [$received];
        }

        if (is_array($expected)) {
            array_unshift($expected, $received);
            return $expected;
        }

        return [$received, $expected];
    }
}
