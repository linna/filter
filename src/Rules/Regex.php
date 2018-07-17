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
 * Check if passed string match a regex
 */
class Regex implements RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'class' => 'Regex',
        'full_class' => __CLASS__,
        'alias' => ['regex', 'rex', 'rx'],
        'args_count' => 1,
        'args_type' => ['string'],
        'has_validate' => true,
        //'has_sanitize' => false
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

        return $this->concreteValidate($args[0], $args[1]);
    }

    /**
     * Concrete validate.
     *
     * @param string $received
     * @param string $regex
     *
     * @return bool
     */
    private function concreteValidate(string $received, string $regex): bool
    {
        $matches = [];

        $result = preg_match($regex, $received, $matches);

        if ($result === 0) {
            $this->message = "Received value must match regex {$regex}";
            return true;
        }

        if ($result === false) {
            $this->message = "Invalid regex provided {$regex}";
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
