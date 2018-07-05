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
 * Check if a number is included or not on interval using ><, <>, >=<, <=> operators.
 */
class NumberInterval extends AbstractNumber implements RuleSanitizeInterface
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['string', 'number', 'number'];

    /**
     * Validate.
     *
     * @param int|float $received
     * @param string    $operator
     * @param int|float $min
     * @param int|float $max
     *
     * @return bool
     */
    public function validate($received, string $operator, $min, $max): bool
    {
        if (!is_numeric($received)) {
            return true;
        }

        if (!is_numeric($min)) {
            return true;
        }

        if (!is_numeric($max)) {
            return true;
        }

        settype($received, 'float');
        settype($min, 'float');
        settype($max, 'float');

        if ((fmod($received, 1.0) === 0.0)) {
            settype($received, 'integer');
            settype($min, 'integer');
            settype($max, 'integer');
        }

        if ($this->switchOperator($operator, $received, $min, $max)) {
            return false;
        }

        return true;
    }

    /**
     * Perform correct operation from passed operator.
     *
     * @param string    $operator
     * @param int|float $numberReceived
     * @param int|float $min
     * @param int|float $max
     *
     * @return bool
     *
     * @throws UnexpectedValueException if unknown operator is provided.
     */
    private function switchOperator(string $operator, &$numberReceived, &$min, &$max): bool
    {
        switch ($operator) {
            case '><': //inside interval exclusive
                return $numberReceived > $min && $numberReceived < $max;
            case '>=<': //inside interval inclusive
                return $numberReceived >= $min && $numberReceived <= $max;
            case '<>': //outside interval exclusive
                return $numberReceived < $min || $numberReceived > $max;
            case '<=>': //outside interval inclusive
                return $numberReceived <= $min || $numberReceived >= $max;;
            default:
                throw new UnexpectedValueException("Unknown comparson operator ({$operator}). Permitted ><, <>, >=<, <=>");
        }
    }
}
