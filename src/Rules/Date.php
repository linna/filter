<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Filter\Rules;

use DateTime;

/**
 * Check required.
 */
class Date
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
    public function validate($received, string $format): bool
    {
        if (($this->date = date_create_from_format($format, $received))) {
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
