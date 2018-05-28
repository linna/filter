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
 * Check if given date is equal or grater than a date.
 */
class DateMin
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['string', 'string'];
    
    /**
     * @var DateTime Valid date.
     */
    private $date;
    
    /**
     * Validate.
     *
     * @return bool
     */
    public function validate($received, string $format, string $min): bool
    {
        $dateReceived = DateTime::createFromFormat($format, $received);
        $dateMin = DateTime::createFromFormat($format, $min);
        
        if (!($dateMin && $dateReceived)) {
            return true;
        }
        
        $dateMin->setTime(0, 0, 0);
        $dateReceived->setTime(0, 0, 0);
        
        if ($dateMin->format('Ymd') <= $dateReceived->format('Ymd')) {
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
    public function sanitize(&$value): void
    {
        $value = $this->date;
    }
}
