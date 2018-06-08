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
class Required
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = [];

    /**
     * Validate.
     *
     * @return bool
     */
    public function validate($received): bool
    {
        if (strlen($received) === 0 || $received === null) {
            return true;
        }

        return false;
    }
}
