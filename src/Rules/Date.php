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

use Linna\Filter\AbstractDate;
use DateTime;

/**
 * Check if one date is valid.
 */
class Date extends AbstractDate
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
     *
     * @var DateTime Valid date in DateTime object.
     */
    private $dateTimeObject;

    /**
     * Validate.
     *
     * @return bool
     */
    public function validate($received, string $format): bool
    {
        if ($this->parseDate($received, $format)) {
            return true;
        }
        
        $this->date = $received;
        
        $dateReceived = DateTime::createFromFormat($format, $received);
        
        if ($this->dateHaveNoTime($format)) {
            $dateReceived->setTime(0, 0, 0);
        }
        
        $this->dateTimeObject = $dateReceived;
        
        return false;
    }

    /**
     * Parse date.
     *
     * @param type $received
     * @param string $format
     *
     * @return bool
     */
    private function parseDate($received, string $format) : bool
    {
        $date = date_parse_from_format($format, $received);

        //set to zero the date
        $month = $day = null;
        
        //set to zero errors
        $warning_count = $error_count = null;
        
        extract($date, EXTR_IF_EXISTS);
        
        settype($month, 'bool');
        settype($day, 'bool');

        if (!$month) {
            return true;
        }

        if (!$day) {
            return true;
        }

        if ($warning_count + $error_count) {
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
