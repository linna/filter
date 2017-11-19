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

use \Linna\Filter\RuleInterface;

/**
 * Check required.
 */
class Required implements RuleInterface
{
    /**
     * @var mixed Received value.
     */
    private $received;
    
    /**
     * @var bool Expected value, not used.
     */
    private $expected;

    /**
     * Class constructor.
     *
     * @param mixed $received
     * @param bool $expected
     */
    public function __construct($received, $expected)
    {
        $this->received = $received;
        $this->expected = $expected;
    }

    /**
     * Test.
     *
     * @return bool
     */
    public function test(): bool
    {
        $data = $this->received;
        
        if (strlen($data) === 0 || $data === '' || $data === null) {
            return true;
        }

        return false;
    }
}
