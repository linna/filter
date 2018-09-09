<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

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

            //exclude from rules array because
            //custom ruless will be built separately
            if ($keyword === 'customrule') {
                continue;
            }

            //get full class name
            $class = __NAMESPACE__."\Rules\\{$class}";

            self::makeAlias($rules, $alias, $keyword, $class::$config);
        }

        return [$rules, $alias];
    }

    /**
     * Build custom rules passed to Filter with .
     *
     * @param array $customRules
     *
     * @return array
     */
    public static function buildCustom(array $customRules): array
    {
        $alias = [];
        $rules = [];

        foreach ($customRules as $rule) {
            $config = $rule->config;
            $config['instance'] = $rule;

            self::makeAlias($rules, $alias, $config['alias'][0], $config);
        }

        return [$rules, $alias];
    }

    /**
     * Make alias array for Filter class.
     *
     * @param array  $rules   Array containing rules
     * @param array  $alias   Array containing rule aliases
     * @param string $keyword Name of the rule class or rule alias for custom rules
     * @param array  $config  Rule configuration
     */
    private static function makeAlias(array &$rules, array &$alias, string $keyword, array $config): void
    {
        //get config for the rule
        $rules[$keyword] = $config;
        //fill array with alias
        $keys = $rules[$keyword]['alias'];
        //fill array of values with name of the class
        $values = array_fill(0, count($keys), $keyword);
        //combine keys and values
        $array = array_combine($keys, $values);
        //add all to alias array
        $alias = array_merge($alias, $array);
        //free memory
        unset($rules[$keyword]['alias']);
    }
}
