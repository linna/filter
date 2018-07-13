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
        $alias = [];
        $rules = [];
        $files = glob(__DIR__.'/Rules/*.php', GLOB_ERR);

        foreach ($files as $entry) {
            //get file name
            $class = basename(str_replace('.php', '', $entry));
            $keyword = strtolower($class);
            //get full class name
            $class = __NAMESPACE__."\Rules\\{$class}";
            //get config for the rule
            $rules[$keyword] = $class::$config;

            //fill array with alias
            $keys = $values = $rules[$keyword]['alias'];
            //fill array of values with name of the class
            $values = array_fill(0, count($keys), $keyword);
            //combine keys and values
            $array = array_combine($keys, $values);
            //add all to alias array
            $alias = array_merge($alias, $array);

            //free memory
            unset($rules[$keyword]['alias']);
        }

        return [$rules, $alias];
    }
}
