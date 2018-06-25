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
 * Abstract Number
 */
class AbstractNumber
{
    /**
     * Sanitize.
     *
     * @param int|float|string $value
     */
    public function sanitize(&$value): void
    {
        if (fmod((float) $value, 1.0) === 0.0) {
            settype($value, 'integer');
            return;
        }

        settype($value, 'float');
    }
}
