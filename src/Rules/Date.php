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
class Date
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['string'];
    
    /**
     * @var DateTime Valid date.
     */
    private $date;
    
    /**
     * Validate.
     *
     * @return bool
     */
    public function validate($received, string $format): bool
    {
        $date = date_parse_from_format($format, $received);
        
        //set to zero the date
        $month = $day = null;
        
        //set to zero errors
        $warning_count = $error_count = null;
        
        extract($date, EXTR_IF_EXISTS);
        
        settype($month, 'bool');
        settype($day, 'bool');
        
        if (!($month || $day)) {
            return true;
        }
        
        if ($warning_count + $error_count) {
            return true;
        }

        $this->date = $date;

        return false;
    }
    
    /**
     * Sanitize.
     *
     * @param mixed $value
     */
    public function sanitize(&$value): void
    {
        //set to zero the date
        $year = $month = $day = null;
        
        //set to zero the time
        $hour = $minute = $second = $fraction = null;
        
        extract($this->date, EXTR_IF_EXISTS);

        //force type if there is bool value
        settype($year, 'integer');
        settype($month, 'integer');
        settype($day, 'integer');
        
        settype($hour, 'integer');
        settype($minute, 'integer');
        settype($second, 'integer');
        
        settype($fraction, 'float');
        
        $second += $fraction;
        
        $value = new DateTime($year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':'.$second);
    }
}
