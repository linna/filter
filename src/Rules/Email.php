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
 * Check if provided is valid.
 */
class Email
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
    public function validate(string $received): bool
    {
        return !filter_var($received, FILTER_VALIDATE_EMAIL);
    }
}
