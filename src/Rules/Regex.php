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

use InvalidArgumentException;

/**
 * Check if passed string match a regex
 */
class Regex implements RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'full_class' => __CLASS__,
        'alias' => ['regex', 'rex', 'rx'],
        'args_count' => 1,
        'args_type' => ['string']
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
     *
     * @throws InvalidArgumentException If a bad regex is provided.
     */
    private function concreteValidate(string $received, string $regex): bool
    {
        $matches = [];

        //error suppressed with @ because if occours preg_match PHP show a warning
        //error replaced with exception
        $result = @preg_match($regex, $received, $matches);

        if ($result === false) {
            throw new InvalidArgumentException("Invalid regex provided {$regex}.");
        }

        if ($result === 0) {
            $this->message = "Received value not match regex {$regex}";
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
