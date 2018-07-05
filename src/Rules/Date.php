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

/**
 * Check if one date is valid.
 */
class Date extends AbstractDate implements RuleInterface
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['string'];

    /**
     * @var string Valid date.
     */
    private $date;

    /**
     * @var DateTime Valid date in DateTime object.
     */
    private $dateTimeObject;

    /**
     * Validate.
     *
     * @param string $received
     * @param string $format
     *
     * @return bool
     */
    public function validate(string $received, string $format): bool
    {
        if ($this->parseDate($received, $format)) {
            return true;
        }

        $dateTimeObject = DateTime::createFromFormat($format, $received);

        if ($dateTimeObject === false) {
            return true;
        }

        if ($this->dateHaveNoTime($format)) {
            $dateTimeObject->setTime(0, 0, 0);
        }

        $this->date = $received;
        $this->dateTimeObject = $dateTimeObject;

        return false;
    }

    /**
     * Parse date.
     *
     * @param string $received
     * @param string $format
     *
     * @return bool
     */
    private function parseDate(string $received, string $format): bool
    {
        $date = date_parse_from_format($format, $received);

        //set to zero the date and the errors
        $month = $day = $warning_count = $error_count = 0;

        extract($date, EXTR_IF_EXISTS);

        if ($day === 0) {
            return true;
        }

        if ($month === 0) {
            return true;
        }

        if ($warning_count + $error_count > 0) {
            return true;
        }

        return false;
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
}
