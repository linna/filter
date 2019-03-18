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
 * Concrete Custom Validate rule class.
 */
class CustomValidate extends AbstractCustom implements RuleValidateInterface
{
    /**
     * Validate.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $args = \func_get_args();

        return !\call_user_func_array($this->callback, $args);
    }
}
