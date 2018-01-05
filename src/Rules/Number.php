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
 * Check required.
 */
class Number
{
    /**
     * Validate.
     *
     * @return bool
     */
    public function validate($received): bool
    {
        return !is_numeric($received);
    }
    
    /**
     * Sanitize.
     *
     * @param mixed $value
     */
    public function sanitize(&$value)
    {
        if (fmod((float) $value, 1.0) === 0.0) {
            settype($value, 'integer');
            return;
        }

        settype($value, 'float');
    }
}
