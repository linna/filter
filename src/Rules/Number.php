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
class Number extends AbstractNumber implements RuleSanitizeInterface
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = [];

    /**
     * Validate.
     *
     * @param int|float $received
     *
     * @return bool
     */
    public function validate($received): bool
    {
        return !is_numeric($received);
    }
}
