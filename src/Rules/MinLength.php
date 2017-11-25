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
 * Check maximum length.
 */
class MinLength
{
    /**
     * Validate.
     *
     * @return bool
     */
    public function validate(string $received, int $minLength): bool
    {
        return strlen($received) < $minLength;
    }
}
