<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Filter\Rules;

use UnexpectedValueException;

/**
 * Compare the length of provided string using >, <, >=, <=, = operators.
 */
class StringLenCompare extends AbstractString implements RuleSanitizeInterface, RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'class' => 'StringLenCompare',
        'full_class' => __CLASS__,
        'alias' => ['stringlencompare', 'strlencmp', 'slc'],
        'args_count' => 2,
        'args_type' => ['string', 'number'],
        'has_validate' => true,
        //'has_sanitize' => true
    ];

    /**
     * @var string Error message
     */
    private $message = '';

    /**
     * Validate.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $args = func_get_args();

        return $this->concreteValidate($args[0], $args[1], $args[2]);
    }

    /**
     * Concrete validate.
     *
     * @param string $received
     * @param string $operator
     * @param int    $compare
     *
     * @return bool
     */
    private function concreteValidate(string $received, string $operator, int $compare): bool
    {
        if ($this->switchOperator($operator, $received, $compare)) {
            return false;
        }

        $this->message = "Received string length is not {$operator} of {$compare}";

        return true;
    }

    /**
     * Perform correct operation from passed operator.
     *
     * @param string $operator
     * @param string $strReceived
     * @param int    $strCompare
     *
     * @return bool
     *
     * @throws UnexpectedValueException if unknown operator is provided.
     */
    private function switchOperator(string $operator, string &$strReceived, int &$strCompare): bool
    {
        switch ($operator) {
            case '>': //greater than
                return strlen($strReceived) > $strCompare;
            case '<': //less than
                return strlen($strReceived) < $strCompare;
            case '>=': //greater than or equal
                return strlen($strReceived) >= $strCompare;
            case '<=': //less than or equal
                return strlen($strReceived) <= $strCompare;
            case '=': //equal
                return strlen($strReceived) === $strCompare;
            case '!=': //equal
                return strlen($strReceived) !== $strCompare;
            default:
                throw new UnexpectedValueException("Unknown comparson operator ({$operator}). Permitted >, <, >=, <=, =, !=");
        }
    }

    /**
     * Return error message.
     *
     * @return string Error message
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
