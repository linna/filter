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
    public static function build() : array
    {
        $dir = dir(__DIR__.'/Rules');
        $rules = [];
            
        while (false !== ($entry = $dir->read())) {
            if ($entry !== '.' && $entry !== '..') {
                $class = str_replace('.php', '', $entry);
                $lower = strtolower($class);
                $args = (new ReflectionClass(__NAMESPACE__."\Rules\\{$class}"))->getDefaultProperties()['arguments'];
                
                $rules[$lower] = ['class' => $class, 'keyword' => $lower, 'args_count' => count($args), 'args_type' => $args];
            }
        }
        
        return $rules;
    }
}
