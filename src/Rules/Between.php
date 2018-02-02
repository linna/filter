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

/**
 * Check if value is between a intervall.
 */
class Between
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['number', 'number'];
    
    /**
     * Validate.
     *
     * @return bool
     */
    public function validate($received, $min, $max): bool
    {
        if ($received < $min || $received > $max) {
            return true;
        }
        
        return false;
    }
}
