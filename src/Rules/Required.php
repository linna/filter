<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Filter\Rules;

/**
 * Check required, value passed must be not null or not 0 length string.
 *
 */
class Required implements RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'class' => 'Required',
        'full_class' => __CLASS__,
        'alias' => ['required', 'req', 'rq'],
        'args_count' => 0,
        'args_type' => [],
        'has_validate' => true,
    ];

    /**
     * @var string Error message
     */
    private $message = '';

    /**
     * Validate.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $args = func_get_args();

        return $this->concreteValidate($args[0]);
    }

    /**
     * Concrete validate.
     *
     * @param mixed $received
     *
     * @return bool
     */
    private function concreteValidate($received): bool
    {
        if ($received === null) {
            $this->message = "Received value is null";
            return true;
        }

        if (strlen((string) $received) === 0) {
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
