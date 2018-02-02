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
 * Description of Parser
 *
 * @author Sebastian
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
    public function parse(array $array, array $rules) : array
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
    private function extractParams(array &$words)
    {
        //intervenire qui per fare in modo che funzioni anche in modalità anonima.
        
        $array = [];
        $actualWord = '';
        $field = $words[0];
        $count = count($words);

        for ($i = 1; $i < $count; $i++) {
            $word = strtolower($words[$i]);

            if (isset($this->rules[$word])) {
                $actualWord = $word;
                $array[$field][$word] = [];
                continue;
            }

            $array[$field][$actualWord][] = $words[$i];
        }
        
        $words = $array;
    }
    
    /**
     * Apply types to rules parameters.
     *
     * @param array $words
     *
     * @throws InvalidArgumentException If unknown keyword is provided.
     */
    private function applyTypes(array &$words)
    {
        $rules = $this->rules;
        $field = key($words);

        foreach ($words[$field] as $key => $word) {
            if (!isset($rules[$key])) {
                throw new InvalidArgumentException("Unknown rule provided ({$field})");
            }
            
            $rule = $rules[$key];
            $keyword = $rule['keyword'];
            
            //first param passed as reference
            $this->castTypes($words[$field][$keyword], $rule['args_type']);
        }
    }
    
    /**
     * Organize rules' array.
     *
     * @param array $words
     */
    private function normalizeParam(array &$words)
    {
        $field = array_keys($words)[0];
        $temp = [];

        foreach ($words[$field] as $key => $word) {
            if (count($word) === 0) {
                $words[$field][$key] = true;
            }

            if (count($word) === 1) {
                $words[$field][$key] = $word[0];
            }

            $temp[] = [$field, $key, $this->rules[$key], $words[$field][$key]];
        }

        $words = $temp;
    }

    /**
     * Apply types when there is one parameter.
     *
     * @param array $params
     * @param array $types
     *
     * @return mixed
     */
    private function castTypes(array &$params, array $types)
    {
        for ($i = 0; $i < count($params); $i++) {
            $type = &$types[$i];
            $param = &$params[$i];
            
            if ($type === 'number') {
                settype($param, $this->strtonum($param));
                continue;
            }

            settype($param, $type);
        }
    }

    /**
     * Identify correct number type.
     *
     * @param string $number
     *
     * @return string
     */
    private function strtonum(string $number): string
    {
        if (fmod((float) $number, 1.0) !== 0.0) {
            return 'float';
        }

        return 'integer';
    }
}
