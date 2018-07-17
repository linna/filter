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
interface RuleValidateInterface extends RuleInterface
{
    /**
     * Validate an input.
     *
     * @return bool
     */
    public function validate(): bool;
}
