<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Filter\Rules;

use Linna\Filter\RuleInterface;

/**
 * Check if value is below a minum.
 */
class Min implements RuleInterface
{
    /**
     * @var mixed Received value.
     */
    private $received;
    
    /**
     * @var mixed Min expected value.
     */
    private $min;

    /**
     *
     * @param mixed $received
     * @param mixed $min
     */
    public function __construct($received, $min)
    {
        $this->received = $received;
        $this->min = $min;
    }

    /**
     * Test.
     *
     * @return bool
     */
    public function test(): bool
    {
        if ($this->received < $this->min) {
            return true;
        }

        return false;
    }
}
