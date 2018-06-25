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
 * Compare two numbers using >, <, >=, <=, = operators.
 */
class NumberCompare extends AbstractNumber
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['string', 'number'];

    /**
     * Validate.
     *
     * @param int|float|string $received
     * @param string           $operator
     * @param int|float|string $compare
     *
     * @return bool
     */
    public function validate($received, string $operator, $compare): bool
    {
        if (!is_numeric($received)) {
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
     * @param string           $operator
     * @param int|float|string $numberReceived
     * @param int|float|string $numberCompare
     *
     * @return bool
     *
     * @throws UnexpectedValueException if unknown operator is provided.
     */
    private function switchOperator(string $operator, &$numberReceived, &$numberCompare): bool
    {
        switch ($operator) {
            case '>': //greater than
                return $numberReceived > $numberCompare;
            case '<': //less than
                return $numberReceived < $numberCompare;
            case '>=': //greater than or equal
                return $numberReceived >= $numberCompare;
            case '<=': //less than or equal
                return $numberReceived <= $numberCompare;
            case '=': //equal
                return $numberReceived === $numberCompare;
            default:
                throw new UnexpectedValueException("Unknown comparson operator ({$operator}). Permitted >, <, >=, <=, =");
        }
    }
}
