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
 * Check if provided is valid.
 */
class Email
{
    /**
     * Validate.
     *
     * @return bool
     */
    public function validate(string $received): bool
    {
        if (filter_var($received, FILTER_VALIDATE_EMAIL) === false) {
            return true;
        }

        return false;
    }
}
