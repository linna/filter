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
 * Check if value is between a intervall.
 */
class Between implements RuleInterface
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
     * @var mixed Max expected value.
     */
    private $max;

    /**
     * Class constructor.
     *
     * @param mixed $received
     * @param mixed $min
     * @param mixed $max
     */
    public function __construct($received, $min, $max)
    {
        $this->received = $received;
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Test.
     *
     * @return bool
     */
    public function test(): bool
    {
        if ($this->received < $this->min || $this->received > $this->max) {
            return true;
        }

        return false;
    }
}
