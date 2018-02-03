<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
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
     * @var array Filters working rules.
     */
    private $rules;
    
    /**
     * Class Constructor.
     */
    public function __construct()
    {
        $this->rules = RuleBuilder::build();
    }
    
    /**
     * Filter one element with given rules.
     *
     * @param mixed $data
     * @param string $rule
     */
    public function filterOne($data, string $rule): void
    {
        $this->data = ['data' => $data];
        $this->interpreteRules(['data '.$rule]);
    }
    
    /**
     * Filter an array of elementes with given rules.
     *
     * @param array $data
     * @param array $rules
     */
    public function filterMulti(array $data, array $rules): void
    {
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
    private function interpreteRules($rules): void
    {
        $parser = new Parser();

        foreach ($rules as $rule) {
            $this->ruleToField(
                $parser->parse(
                    Lexer::tokenize($rule),
                    $this->rules
                )
            );
        }
    }

    /**
     * Apply rules to a field.
     *
     * @param array $rules
     */
    private function ruleToField(array $rules): void
    {
        foreach ($rules as $rule) {
            $field = $rule[0];
            $filter = $rule[2]['class'];

            $class = 'Linna\Filter\Rules\\' . $filter;
            $refClass = new ReflectionClass($class);

            $instance = $refClass->newInstance();

            if (!isset($this->data[$field])) {
                $this->errors++;
                $this->messages[$field][$filter] = "Form field '{$field}' missing.";
                continue;
            }

            if ($refClass->hasMethod('validate')) {
                $refMethod = new ReflectionMethod($class, 'validate');

                if ($refMethod->invokeArgs($instance, $this->getArguments($rule[2]['args_count'], $rule[3], $this->data[$field]))) {
                    $this->errors++;
                    $this->messages[$field][$filter] = ['expected' => $rule[3], 'received' => $this->data[$field]];
                    continue;
                }
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
