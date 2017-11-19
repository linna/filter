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

/**
 * Filter.
 */
class Filter
{
    /**
     * @var array Rules for filtering.
     */
    private $rules = [];
    
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
        $this->rules = $rules;
        $this->data = $data;

        $this->getRules();
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
     * Get parsed rules.
     */
    private function getRules()
    {
        $rules = $this->rules;
        
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
            $expected = $rule[3];
            $args = [];
            
            $received = (isset($this->data[$field])) ? $this->data[$field] : '';

            if (is_array($expected)) {
                array_unshift($expected, $received);
                $args = $expected;
            }

            if (!is_array($expected)) {
                $args = [$received, $expected];
            }

            $instance = (new ReflectionClass('Linna\Filter\Rules\\' . $filter))->newInstanceArgs($args);

            if ($instance->test()) {
                $this->errors++;
                $this->messages[$field][$filter] = ['expected' => $expected, 'received' => $received];
            }
        }
    }
}
