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

/**
 * Check if value is below a minum.
 */
class Min
{
    /**
     * Validate.
     *
     * @return bool
     */
    public function validate($received, $min): bool
    {
        if ($received < $min) {
            return true;
        }

        return false;
    }
}
