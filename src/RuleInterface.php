<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Filter;

/**
 * Rule Interface.
 */
interface RuleInterface
{
    /**
     * Return test result.
     */
    public function test(): bool;
}
