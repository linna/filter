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
        if ($this->date = DateTime::createFromFormat($format, $received)) {
            $this->date->setTime(0, 0, 0);
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
