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
 * Rule Sanitize Interface
 */
interface RuleSanitizeInterface extends RuleInterface
{
    /**
     * Sanitize an input.
     *
     * @param mixed $value
     */
    public function sanitize(&$value): void;
}
