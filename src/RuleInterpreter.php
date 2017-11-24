<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Filter;

/**
 * Translate rules from phrase to array.
 */
class RuleInterpreter
{
    /**
     * @var array Accepted rules.
     */
    private static $keywords = [
        'required' => ['Required', 'boolean', 0],
        'number' => ['Number', 'boolean', 0],
        'email' => ['Email', 'boolean', 0],
        'min' => ['Min', 'number', 1],
        'max' => ['Max', 'number', 1],
        'between' => ['Between', 'number', 2],
        'length' => ['Length', 'number', 1],
        'maxlength' => ['MaxLength', 'number', 1],
        'minlength' => ['MinLength', 'number', 1],
        'date' => ['Date', 'string', 1],
        'datebefore' => ['DateBefore', 'string', 1],
        'dateafter' => ['DateAfter', 'string', 1],
        'datebetween' => ['DateBetween', 'string', 2],
        'use' => ['Use', 'string', 1]
    ];
    
    /**
     * @var string Phrase to be interpreted.
     */
    private $phrase;

    /**
     * Class contructor.
     *
     * @param string $phrase
     */
    public function __construct(string $phrase)
    {
        $this->phrase = $phrase;
    }

    /**
     * Return interpreted rules.
     *
     * @return array
     */
    public function get(): array
    {
        $words = $this->lexer($this->phrase);

        $this->parser($words);
        
        return $words;
    }

    /**
     * Lexer.
     *
     * @param string $period
     * @return array
     */
    private function lexer(string $period) : array
    {
        $chars = str_split(rtrim(ltrim($period)));
        
        $words = $temp = [];
        $word = 0;

        foreach ($chars as $char) {
            if (in_array(ord($char), [32, 44, 58, 59])) {
                $words[$word++] = implode('', $temp);
                $temp = [];
                continue;
            }

            $temp[] = $char;
        }

        $words[$word] = implode('', $temp);

        return array_values(array_filter($words, 'trim'));
    }

    /**
     * Parser.
     *
     * @param array $array
     */
    private function parser(array &$array)
    {
        $this->parserExtractParams($array);
        $this->parserNormalizeParam($array);
        $this->parserApplyTypes($array);
    }
    
    /**
     * Separate keywords from parameters.
     *
     * @param array $words
     */
    private function parserExtractParams(array &$words)
    {
        $array = [];
        $actualWord = '';
        $field = $words[0];
        $count = count($words);
        
        for ($i = 1; $i < $count; $i++) {
            $word = $words[$i];
            
            if (isset(self::$keywords[$word])) {
                $actualWord = $word;
                $array[$field][$word] = [];
                continue;
            }

            $array[$field][$actualWord][] = $word;
        }
        
        $words = $array;
    }

    /**
     * Organize rules' array.
     *
     * @param array $words
     */
    private function parserNormalizeParam(array &$words)
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
            
            $temp[] = [$field, $key, self::$keywords[$key], $words[$field][$key]];
        }
        
        $words = $temp;
    }

    /**
     * Apply types to rules parameters.
     *
     * @param array $words
     *
     * @throws \InvalidArgumentException If unknow keyword is provided.
     */
    private function parserApplyTypes(array &$words)
    {
        $rules = &self::$keywords;

        foreach ($words as $key => $word) {
            $rule = &$words[$key][1];
            $params = &$words[$key][3];
            
            if (!isset($rules[$rule])) {
                throw new \InvalidArgumentException('Unknow rule keyword provided');
            }
            
            if (is_array($params)) {
                $this->parserTypeCastingArray($params, $rules[$rule][1]);
                continue;
            }

            $this->parserTypeCastingOthers($params, $rules[$rule][1]);
        }
    }

    /**
     * Apply types when there is more than one parameter.
     *
     * @param array $params
     * @param string $type
     */
    private function parserTypeCastingArray(array &$params, string &$type)
    {
        foreach ($params as $key => $value) {
            $this->parserTypeCastingOthers($params[$key], $type);
        }
    }

    /**
     * Apply types when there is one parameter.
     *
     * @param mixed $param
     * @param string $type
     *
     * @return void
     */
    private function parserTypeCastingOthers(&$param, string &$type)
    {
        if ($type === 'number') {
            settype($param, $this->strtonum($param));
            return;
        }

        settype($param, $type);
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
        if (fmod((float) $number, 1.0) === 0.0) {
            return 'integer';
        }

        return 'float';
    }
}
