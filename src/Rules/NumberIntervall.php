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
 * Check if a number in inside or outside an interval using ><, <>, >=<, <=> operators.
 */
class NumberIntervall extends AbstractNumber
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['string', 'number', 'number'];

    /**
     * Validate.
     *
     * @param int|float|string $received
     * @param string           $operator
     * @param int|float|string $min
     * @param int|float|string $max
     *
     * @return bool
     */
    public function validate($received, string $operator, $min, $max): bool
    {
        if (!is_numeric($received)) {
            return true;
        }

        if ($this->switchOperator($operator, $received, $min, $max)) {
            return false;
        }

        return true;
    }

    /**
     * Perform correct operation from passed operator.
     *
     * @param string           $operator
     * @param int|float|string $numberReceived
     * @param int|float|string $min
     * @param int|float|string $max
     *
     * @return bool
     *
     * @throws UnexpectedValueException if unknown operator is provided.
     */
    private function switchOperator(string $operator, &$numberReceived, &$min, &$max): bool
    {
        switch ($operator) {
            case '><': //between exclusive
                return $numberReceived > $min && $numberReceived < $max;
            case '>=<': //between inclusive
                return $numberReceived >= $min && $numberReceived <= $max;
            case '<>': //outside intervall exclusive
                return $numberReceived < $min || $numberReceived > $max;
            case '<=>': //outside intervall inclusive
                return $numberReceived <= $min || $numberReceived >= $max;;
            default:
                throw new UnexpectedValueException("Unknown comparson operator ({$operator}). Permitted ><, <>, >=<, <=>");
        }
    }
}
