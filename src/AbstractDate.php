<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Filter;

class AbstractDate
{
    /**
     * Check if format contain time keywords.
     *
     * @param string $format
     * @return bool
     */
    protected function dateHaveNoTime(string $format): bool
    {
        foreach (['a','A','B','g','G','h','H','i','s','u','v'] as $char) {
            if (strpos($format, $char) !== false) {
                return false;
            }
        }

        return true;
    }
}
