<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Filter;

use InvalidArgumentException;
use Linna\Filter\Rules\RuleSanitizeInterface;
use Linna\Filter\Rules\RuleValidateInterface;
use Linna\Filter\Result;

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
     * @var array Filters rules.
     */
    private $rules = [];

    /**
     * @var array Rule aliases.
     */
    private $alias = [];

    /**
     * Class Constructor.
     */
    public function __construct()
    {
        [$this->rules, $this->alias] = RuleBuilder::build();
    }

    /**
     * Filter.
     *
     * @param mixed        $data
     * @param array|string $rule
     *
     * @throws InvalidArgumentException If rule isn't a string when used for only
     *                                  one value. If data and rule aren't array
     *                                  when used for multi values.
     *
     * @return Result
     */
    public function filter($data, $rule): Result
    {
        if (is_array($data) && is_array($rule)) {
            $this->sanitizedData = $this->data = $data;
            $this->interpreteRules($rule);

            return $this->buildResultObject();
        }

        if (is_string($rule)) {
            $this->sanitizedData = $this->data = ['data' => $data];
            $this->interpreteRules(['data '.$rule]);

            return $this->buildResultObject();
        }

        throw new InvalidArgumentException('Invalid types passed for data or rules.');
    }

    /**
     * Build anonymous class contain results of filtering.
     *
     * @return Result
     */
    private function buildResultObject(): Result
    {
        return new class($this->sanitizedData, $this->messages, $this->errors) extends Result {
        };
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
                    $this->rules,
                    $this->alias
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
            $ruleProps = $this->rules[$rule[1]];
            $ruleParams = $rule[2];

            $instance = new $ruleProps['full_class']();

            //initialize message array
            $this->messages[$field] = $this->messages[$field] ?? [];

            //check if value is isset in data
            if ($this->checkValue($field)) {
                continue;
            }

            //invoke sanitize section of the filter
            //if filter fail go to next rule
            if ($this->invokeValidate($instance, $field, $ruleParams)) {
                continue;
            }

            //invoke sanitize section of the filter
            if ($instance instanceof RuleSanitizeInterface) {
                $instance->sanitize($this->sanitizedData[$field]);
            }
        }
    }

    /**
     * Check if a field exist in data.
     *
     * @param string $field
     *
     * @return bool
     */
    private function checkValue(string &$field): bool
    {
        if (isset($this->data[$field])) {
            return false;
        }

        $this->errors++;
        $this->messages[$field][] = "Form field '{$field}' missing.";

        return true;
    }

    /**
     * Invoke validate.
     *
     * @param RuleValidateInterface $instance
     * @param string                $field
     * @param array                 $ruleParams
     *
     * @return bool
     */
    private function invokeValidate(RuleValidateInterface &$instance, string $field, array $ruleParams): bool
    {
        array_unshift($ruleParams, $this->data[$field]);

        if (call_user_func_array(array($instance, 'validate'), $ruleParams)) {
            $this->errors++;

            $message = $instance->getMessage();

            if (strlen($message)) {
                $this->messages[$field][] = $message;
            }

            return true;
        }

        return false;
    }
}
