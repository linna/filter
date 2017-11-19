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
class MaxLength implements RuleInterface
{
    /**
     * @var string Received value.
     */
    private $received;
    
    /**
     * @var int Max allowed length.
     */
    private $maxLength;

    /**
     * Class constructor.
     *
     * @param string $received
     * @param int $maxLength
     */
    public function __construct(string $received, int $maxLength)
    {
        $this->received = $received;
        $this->maxLength = $maxLength;
    }

    /**
     * Test.
     *
     * @return bool
     */
    public function test(): bool
    {
        if (strlen($this->received) > $this->maxLength) {
            return true;
        }

        return false;
    }
}
