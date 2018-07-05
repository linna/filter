<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Filter\Rules;

use UnexpectedValueException;

/**
 * Compare two strings using >, <, >=, <=, = operators.
 */
class StringCompare extends AbstractString implements RuleSanitizeInterface
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['string', 'string'];

    /**
     * Validate.
     *
     * @param string $received
     * @param string $operator
     * @param mixed $compare
     * @return bool
     */
    public function validate(string $received, string $operator, $compare): bool
    {
        if (!is_string($received)) {
            return true;
        }

        if ($this->switchOperator($operator, $received, $compare)) {
            return false;
        }

        return true;
    }

    /**
     * Perform correct operation from passed operator.
     *
     * @param string $operator
     * @param string $strReceived
     * @param int|float|string $strCompare
     *
     * @return bool
     *
     * @throws UnexpectedValueException if unknown operator is provided.
     */
    private function switchOperator(string $operator, string &$strReceived, &$strCompare): bool
    {
        switch ($operator) {
            case 'len>': //greater than
                return strlen($strReceived) > (int) $strCompare;
            case 'len<': //less than
                return strlen($strReceived) < (int) $strCompare;
            case 'len>=': //greater than or equal
                return strlen($strReceived) >= (int) $strCompare;
            case 'len<=': //less than or equal
                return strlen($strReceived) <= (int) $strCompare;
            case 'len=': //equal
                return strlen($strReceived) === (int) $strCompare;
            case '=': //equal
                return $strReceived === $strCompare;
            default:
                throw new UnexpectedValueException("Unknown comparson operator ({$operator}). Permitted >, <, >=, <=, =");
        }
    }
}
