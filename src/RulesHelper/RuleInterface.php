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
 * Rule Interface
 */
interface RuleInterface
{
    /**
     * Return error message.
     *
     * @return string Error message
     */
    public function getMessage(): string;
}
