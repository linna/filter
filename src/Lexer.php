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
 * Lexer.
 */
class Lexer
{
    /**
     * Split period in tokens.
     *
     * @param string $period
     *
     * @return array
     */
    public function tokenize(string $period): array
    {
        $chars = str_split(rtrim(ltrim($period)));
        $count = count($chars);
        $words = $temp = [];

        for ($i = 0; $i < $count; $i++) {
            $char = $chars[$i];
            $ord = ord($char);

            //treat delimited string separately
            //this fix some problems with regex rule
            if (in_array($ord, [34, 35, 39, 47, 126])) {
                $temp[] = $this->mergeDelimitedString($count, $ord, $i, $chars);
                continue;
            }

            if (in_array($ord, [32, 44, 58, 59])) {
                $words[] = implode('', $temp);
                $temp = [];
                continue;
            }

            $temp[] = $char;
        }

        $words[] = implode('', $temp);

        return array_values(array_filter($words, 'trim'));
    }

    /**
     * Merge delimited string separately from main lexer cicle.
     *
     * @param int   $count Size of chars array.
     * @param int   $ord   Delimiter that triggered this method.
     * @param int   $i     Main cilce counter.
     * @param array $chars Chars of period.
     *
     * @return string
     */
    private function mergeDelimitedString(int $count, int $ord, int &$i, array &$chars): string
    {
        $tmp = [];

        while (++$i < $count) {
            $char = $chars[$i];

            if ($ord === ord($char)) {
                break;
            }

            $tmp[] = $char;
        }

        //fix for regex, add delimiter
        if (in_array($ord, [35, 47, 126])) {
            array_unshift($tmp, chr($ord));
            array_push($tmp, chr($ord));
        }

        return implode('', $tmp);
    }
}
