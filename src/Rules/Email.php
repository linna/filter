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
 * Check if provided email is valid.
 */
class Email implements RuleInterface
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
     * @param string $received
     *
     * @return bool
     */
    public function validate(string $received): bool
    {
        if (!filter_var($received, FILTER_VALIDATE_EMAIL)) {
            $this->message = "Received string is an invalid e-mail address";
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
