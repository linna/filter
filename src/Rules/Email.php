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
class Email implements RuleInterface
{
    /**
     * @var string Received value.
     */
    private $received;
    
    /**
     * @var bool Expected value, not used.
     */
    private $expected;

    /**
     * Class constructor.
     *
     * @param string $received
     * @param bool $expected
     */
    public function __construct(string $received, bool $expected)
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
        $data = filter_var($this->received, FILTER_VALIDATE_EMAIL);
        
        var_dump($data);
        
        if ($data === false) {
            return true;
        }

        return false;
    }
}
