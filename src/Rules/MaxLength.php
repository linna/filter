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
 * Check maximum length.
 */
class MaxLength
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = ['string', 'string'];

    /**
     * Validate.
     *
     * @return bool
     */
    public function validate(string $received, int $maxLength): bool
    {
        return strlen($received) > $maxLength;
    }
}
