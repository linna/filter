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
    private $arguments = ['string', 'string'];

    /**
     * Validate.
     *
     * @param mixed $received
     * @param string $operator
     * @param mixed $compare
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
     * @param string $operator
     * @param mixed $numberReceived
     * @param mixed $numberCompare
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
                var_dump($numberReceived);
                var_dump($numberCompare);
                return $numberReceived === $numberCompare;
            default:
                throw new UnexpectedValueException("Unknown comparson operator ({$operator}). Permitted >, <, >=, <=, =");
        }
    }
}
