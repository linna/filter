<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Filter;

use ReflectionClass;

/**
 * Build filter rules from files in rule directory.
 */
class RuleBuilder
{
    /**
     * Build rules from classes files.
     *
     * @return array
     */
    public static function build(): array
    {
        $rules = [];
        $files = glob(__DIR__.'/Rules/*.php', GLOB_ERR);

        foreach ($files as $entry) {
            $class = basename(str_replace('.php', '', $entry));
            $ruleKeyword = strtolower($class);

            $reflection = new ReflectionClass(__NAMESPACE__."\Rules\\{$class}");
            $args = $reflection->getDefaultProperties()['arguments'];
            $hasValidate = $reflection->hasMethod('validate');
            $hasSanitize = $reflection->hasMethod('sanitize');

            $rules[$ruleKeyword] = [
                'class' => $class,
                'full_class' => 'Linna\Filter\Rules\\'.$class,
                'keyword' => $ruleKeyword,
                'args_count' => count($args),
                'args_type' => $args,
                'has_validate' => $hasValidate,
                'has_sanitize' => $hasSanitize
            ];
        }

        return $rules;
    }
}
