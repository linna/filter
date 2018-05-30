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

use DateTime;
use UnexpectedValueException;

/**
 * Compare two dates using >, <, >=, <=, = operators.
 */
class DateCompare extends AbstractDate
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['string', 'string', 'string'];

    /**
     * @var string Valid date.
     */
    private $date;

    /**
     *
     * @var DateTime Valid date in DateTime object.
     */
    private $dateTimeObject;

    /**
     * Validate.
     *
     * @return bool
     */
    public function validate($received, string $operator, string $format, string $compare): bool
    {
        $dateReceived = DateTime::createFromFormat($format, $received);
        $dateCompare = DateTime::createFromFormat($format, $compare);

        if (!($dateReceived && $dateCompare)) {
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
     * @param string $operator
     * @param DateTime $dateReceived
     * @param DateTime $dateCompare
     *
     * @return bool
     *
     * @throws UnexpectedValueException if unknown operator is provided.
     */
    private function switchOperator(string $operator, DateTime &$dateReceived, DateTime &$dateCompare): bool
    {
        $received = (int) $dateReceived->format('YmdHis');
        $compare = (int) $dateCompare->format('YmdHis');
        
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
}
