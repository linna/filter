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
 * Check maximum length.
 */
class MinLength implements RuleInterface
{
    /**
     * @var string Received value.
     */
    private $received;
    
    /**
     * @var int Min allowed length.
     */
    private $minLength;

    /**
     * Class constructor.
     *
     * @param string $received
     * @param int $minLength
     */
    public function __construct(string $received, int $minLength)
    {
        $this->received = $received;
        $this->minLength = $minLength;
    }

    /**
     * Test.
     *
     * @return bool
     */
    public function test(): bool
    {
        if (strlen($this->received) > $this->minLength) {
            return true;
        }

        return false;
    }
}
