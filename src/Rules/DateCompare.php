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

use DateTime;
use UnexpectedValueException;

/**
 * Compare two dates using >, <, >=, <=, = operators.
 */
class DateCompare extends AbstractDate implements RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'class' => 'DateCompare',
        'full_class' => __CLASS__,
        'alias' => ['datecompare', 'datcmp', 'dc'],
        'args_count' => 3,
        'args_type' => ['string', 'string', 'string'],
        'has_validate' => true,
    ];

    /**
     * @var string Valid date.
     */
    private $date;

    /**
     * @var DateTime Valid date in DateTime object.
     */
    private $dateTimeObject;

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
     * @param string $received
     * @param string $operator
     * @param string $format
     * @param string $compare
     *
     * @return bool
     */
    private function concreteValidate(string $received, string $operator, string $format, string $compare): bool
    {
        $dateReceived = DateTime::createFromFormat($format, $received);
        $dateCompare = DateTime::createFromFormat($format, $compare);

        if (!($dateReceived && $dateCompare)) {
            $this->message = "Received date is not in expected format {$format}";
            return true;
        }

        if ($this->dateHaveNoTime($format)) {
            $dateReceived->setTime(0, 0, 0);
            $dateCompare->setTime(0, 0, 0);
        }

        if ($this->switchOperator($operator, $dateReceived, $dateCompare)) {
            $this->date = $dateReceived->format($format);
            $this->dateTimeObject = $dateReceived;
            return false;
        }

        $this->message = "Received date is not {$operator} {$compare}";

        return true;
    }

    /**
     * Return DateTime object.
     *
     * @return DateTime
     */
    public function getDateTimeObject(): DateTime
    {
        return $this->dateTimeObject;
    }

    /**
     * Perform correct operation from passed operator.
     *
     * @param string   $operator
     * @param DateTime $dateReceived
     * @param DateTime $dateCompare
     *
     * @return bool
     *
     * @throws UnexpectedValueException if unknown operator is provided.
     */
    private function switchOperator(string $operator, DateTime &$dateReceived, DateTime &$dateCompare): bool
    {
        $received = $dateReceived->format(DateTime::ATOM);
        $compare = $dateCompare->format(DateTime::ATOM);

        switch ($operator) {
            case '>': //greater than
                return $received > $compare;
            case '<': //less than
                return $received < $compare;
            case '>=': //greater than or equal
                return $received >= $compare;
            case '<=': //less than or equal
                return $received <= $compare;
            case '=': //equal
                return $received === $compare;
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
