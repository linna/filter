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
class Required implements RuleInterface
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
     * @param mixed $received
     *
     * @return bool
     */
    public function validate($received): bool
    {
        if ($received === null) {
            $this->message = "Received value is null";
            return true;
        }

        if (!strlen((string)$received)) {
            $this->message = "Received value is a void string";
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
