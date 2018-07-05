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

use Linna\Filter\Rules\Number;
use OutOfBoundsException;

/**
 * Parser
 */
class Parser
{
    /**
     * @var array Parsing rules.
     */
    private $rules;

    /**
     * Parser.
     *
     * @param array $array
     */
    public function parse(array $array, array $rules): array
    {
        $this->rules = $rules;

        $this->extractParams($array);
        $this->applyTypes($array);
        $this->normalizeParam($array);

        return $array;
    }

    /**
     * Separate keywords from parameters.
     *
     * @param array $words
     */
    private function extractParams(array &$words): void
    {
        $array = [];
        $field = $words[0];
        $count = count($words);

        $arguments = 0;

        for ($i = 1, $c = -1; $i < $count; $i++) {
            $word = strtolower($words[$i]);

            if (isset($this->rules[$word])) {
                $arguments = $this->rules[$word]['args_count'];
                $array[$field][++$c] = [$word];

                continue;
            }

            if (--$arguments < 0) {
                throw new OutOfBoundsException("Unknown filter provided ({$word})");
            }

            $array[$field][$c][] = $words[$i];
        }

        $words = $array;
    }

    /**
     * Apply types to rules parameters.
     *
     * @param array $words
     */
    private function applyTypes(array &$words): void
    {
        $rules = $this->rules;
        $field = key($words);

        foreach ($words[$field] as $key => $word) {
            $rule = $words[$field][$key][0];

            //first param passed as reference
            $this->castTypes($words[$field][$key], $rules[$rule]['args_type']);
        }
    }

    /**
     * Apply types when there is one parameter.
     *
     * @param array $params
     * @param array $types
     */
    private function castTypes(array &$params, array $types): void
    {
        $number = new Number();
        $count = count($params);

        for ($i = 1,$k = 0; $i < $count; $i++) {
            $type = &$types[$k++];
            $param = &$params[$i];

            if ($type === 'number') {
                $number->sanitize($param);
                continue;
            }

            settype($param, $type);
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

        foreach ($words[$field] as $key => $word) {
            $rule = $word[0];

            array_shift($word);

            $temp[] = [$field, $rule, $word];
        }

        $words = $temp;
    }
}
