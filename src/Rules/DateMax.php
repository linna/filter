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
 * Check if given date is equal or less than a date.
 */
class DateMax
{
    /**
     * @var DateTime Valid date.
     */
    private $date;
    
    /**
     * Validate.
     *
     * @return bool
     */
    public function validate($received, string $format, string $max): bool
    {
        $dateReceived = DateTime::createFromFormat($format, $received);
        $dateMax = DateTime::createFromFormat($format, $max);
        
        if (!($dateMax && $dateReceived)) {
            return true;
        }
        
        $dateMax->setTime(0, 0, 0);
        $dateReceived->setTime(0, 0, 0);
            
        if ($dateMax->format('Ymd') >= $dateReceived->format('Ymd')) {
            $this->date = $dateReceived;
            return false;
        }
        
        return true;
    }
    
    /**
     * Sanitize.
     *
     * @param mixed $value
     */
    public function sanitize(&$value)
    {
        $value = $this->date;
    }
}
