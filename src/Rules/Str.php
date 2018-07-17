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
 * Check if provided value is a string.
 *
 */
class Str extends AbstractString implements RuleSanitizeInterface, RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'class' => 'Str1ng',
        'full_class' => __CLASS__,
        'alias' => ['string', 'str', 's'],
        'args_count' => 0,
        'args_type' => [],
        'has_validate' => true,
        //'has_sanitize' => true
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
        if (!is_string($received)) {
            $this->message = "Received value is not a string";
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
