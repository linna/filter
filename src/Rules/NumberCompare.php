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
 * Compare two numbers using >, <, >=, <=, = operators.
 */
class NumberCompare extends AbstractNumber implements RuleSanitizeInterface, RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'class' => 'NumberCompare',
        'full_class' => __CLASS__,
        'alias' => ['numbercompare', 'numcmp', 'nc'],
        'args_count' => 2,
        'args_type' => ['string', 'number'],
        'has_validate' => true,
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
     * @param int|float $received
     * @param string    $operator
     * @param int|float $compare
     *
     * @return bool
     */
    private function concreteValidate($received, string $operator, $compare): bool
    {
        if (!is_numeric($received)) {
            return true;
        }

        if (!is_numeric($compare)) {
            return true;
        }

        $received = (float) $received;
        $compare = (float) $compare;

        if ($this->switchOperator($operator, $received, $compare)) {
            return false;
        }

        $this->message = "Received number is not {$operator} {$compare}";

        return true;
    }

    /**
     * Perform correct operation from passed operator.
     *
     * @param string    $operator
     * @param int|float $numberReceived
     * @param int|float $numberCompare
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
                return !($numberReceived - $numberCompare);
            default:
                throw new UnexpectedValueException("Unknown comparson operator ({$operator}). Permitted >, <, >=, <=, =");
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
