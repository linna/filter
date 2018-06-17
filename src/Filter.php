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
     * @var array Sanitized data.
     */
    private $sanitizedData = [];

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
        $this->sanitizedData = $this->data = ['data' => $data];
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
        $this->sanitizedData = $this->data = $data;
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
        return $this->sanitizedData;
    }

    /**
     * Get parsed rules.
     */
    private function interpreteRules($rules): void
    {
        $parser = new Parser();
        $lexer = new Lexer();

        foreach ($rules as $rule) {
            $this->ruleToField(
                $parser->parse(
                    $lexer->tokenize($rule),
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

            //check if value is isset in data
            if ($this->checkValue($field, $filter)) {
                continue;
            }

            if ($this->invokeValidate($refClass, $class, $field, $rule, $filter, $instance)) {
                continue;
            }

            $this->invokeSanitize($refClass, $field, $instance);
        }
    }

    /**
     * Check if a field exist in data.
     *
     * @param string $field
     * @param string $filter
     *
     * @return bool
     */
    private function checkValue(string &$field, string &$filter): bool
    {
        if (isset($this->data[$field])) {
            return false;
        }

        $this->errors++;
        $this->messages[$field][$filter] = "Form field '{$field}' missing.";

        return true;
    }

    /**
     * Invoke validate.
     *
     * @param ReflectionClass $refClass
     * @param string $class
     * @param string $field
     * @param array $rule
     * @param string $filter
     * @param mixed $instance
     *
     * @return bool
     */
    private function invokeValidate(
        ReflectionClass &$refClass,
        string &$class,
        string &$field,
        array &$rule,
        string &$filter,
        &$instance
    ): bool {
        if ($refClass->hasMethod('validate')) {
            if ((new ReflectionMethod($class, 'validate'))->invokeArgs($instance, $this->getArguments($rule[2]['args_count'], $rule[3], $this->data[$field]))) {
                $this->errors++;
                $this->messages[$field][$filter] = ['expected' => $rule[3], 'received' => $this->data[$field]];

                return true;
            }
        }

        return false;
    }

    /**
     * Invoke Sanitize.
     *
     * @param ReflectionClass $refClass
     * @param string $field
     * @param mixed $instance
     */
    private function invokeSanitize(ReflectionClass &$refClass, string &$field, &$instance): void
    {
        if ($refClass->hasMethod('sanitize')) {
            $instance->sanitize($this->sanitizedData[$field]);
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
