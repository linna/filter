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

use Linna\Filter\Rules\Number;
use OutOfBoundsException;

/**
 * Parser.
 *
 */
class Parser
{
    /**
     * @var array Filters rules.
     */
    private $rules = [];

    /**
     * @var array Rule aliases.
     */
    private $alias = [];

    /**
     * Parse user defined rules.
     *
     * @param array $tokens Tokens from lexer
     * @param array $rules  Rule properties
     * @param array $alias  Alises for a rule
     *
     * @return array
     */
    public function parse(array $tokens, array $rules, array $alias): array
    {
        $this->rules = $rules;
        $this->alias = $alias;

        $this->extractParams($tokens);
        $this->applyTypesToParams($tokens);
        $this->normalizeParam($tokens);

        return $tokens;
    }

    /**
     * Separate keywords from parameters.
     *
     * @param array $words
     */
    private function extractParams(array &$words): void
    {
        $array = [];
        $field = array_shift($words);

        while (count($words)) {
            $word = strtolower($words[0]);

            if (isset($this->alias[$word])) {
                //replace the alias fo fix missing class error
                //when call applyTypesToParams
                $word = $words[0] = $this->alias[$word];
                $args = $this->rules[$word]['args_count'];

                $array[$field][] = array_splice($words, 0, (int) ++$args);
                continue;
            }

            throw new OutOfBoundsException("Unknown filter provided for field ({$field})");
        }

        $words = $array;
    }

    /**
     * Apply types to rules parameters.
     *
     * @param array $words
     */
    private function applyTypesToParams(array &$words): void
    {
        $rules = $this->rules;
        $field = &$words[key($words)];

        //needed
        $number = new Number();

        //word variable need reference for point to value
        foreach ($field as &$word) {
            $types = $rules[$word[0]]['args_type'];

            foreach ($types as $key => $type) {
                $param = &$word[$key + 1];

                if ($type === 'number') {
                    $number->sanitize($param);
                    continue;
                }

                settype($param, $type);
            }
        }
    }

    /**
     * Organize rules' array.
     *
     * @param array $words
     */
    private function normalizeParam(array &$words): void
    {
        $field = array_keys($words)[0];
        $temp = [];

        foreach ($words[$field] as $word) {
            $rule = $word[0];

            //remove the first element from the array
            //it's the name of the rule
            array_shift($word);

            $temp[] = [$field, $rule, $word];
        }

        $words = $temp;
    }
}
