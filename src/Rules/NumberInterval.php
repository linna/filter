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
 * Check if a number is included or not on interval using ><, <>, >=<, <=> operators.
 */
class NumberInterval extends AbstractNumber implements RuleSanitizeInterface, RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'class' => 'NumberInterval',
        'full_class' => __CLASS__,
        'alias' => ['numberinterval', 'numint', 'ni'],
        'args_count' => 3,
        'args_type' => ['string', 'number', 'number'],
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

        return $this->concreteValidate($args[0], $args[1], $args[2], $args[3]);
    }

    /**
     * Concrete validate.
     *
     * @param int|float $received
     * @param string    $operator
     * @param int|float $min
     * @param int|float $max
     *
     * @return bool
     */
    private function concreteValidate($received, string $operator, $min, $max): bool
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

        $received = (float) $received;
        $min = (float) $min;
        $max = (float) $max;

        if ($this->switchOperator($operator, $received, $min, $max)) {
            return false;
        }

        $this->message = "Received number is not {$min} {$operator} {$max}";

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
                return $numberReceived <= $min || $numberReceived >= $max; ;
            default:
                throw new UnexpectedValueException("Unknown comparson operator ({$operator}). Permitted ><, <>, >=<, <=>");
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
