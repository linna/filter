<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Filter\Rules;

/**
 * Concrete Custom Sanitize rule class.
 */
class CustomSanitize extends AbstractCustom implements RuleSanitizeInterface
{
    /**
     * Sanitize.
     *
     * @param mixed $value
     */
    public function sanitize(&$received): void
    {
        //http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.variable-handling.indirect
        ($this->callback)($received);
    }
}
