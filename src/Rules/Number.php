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
 * Check if provided value is a number.
 */
class Number extends AbstractNumber implements RuleSanitizeInterface
{
    /**
     * @var array Arguments expected.
     */
    private $arguments = [];

    /**
     * @var string Error message
     */
    private $message = '';

    /**
     * Validate.
     *
     * @param int|float $received
     *
     * @return bool
     */
    public function validate($received): bool
    {
        if (!is_numeric($received)) {
            $this->message = "Received value is not a number";
            return true;
        }

        return false;
    }

    /**
     * Return error message.
     *
     * @return string Error message
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
