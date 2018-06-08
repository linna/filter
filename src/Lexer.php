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
 * Lexer.
 */
class Lexer
{
    /**
     * Split period in tokens.
     *
     * @param string $period
     * @return array
     */
    public function tokenize(string $period): array
    {
        $chars = str_split(rtrim(ltrim($period)));
        $words = $temp = [];
        $string = 0;

        foreach ($chars as $char) {
            if (ord($char) === 39) {
                $string++;
                continue;
            }

            if ($string === 1) {
                $temp[] = $char;
                continue;
            }

            if ($string === 2) {
                $words[] = implode('', $temp);
                $temp = [];
                $string = 0;
                continue;
            }

            if (in_array(ord($char), [32, 44, 58, 59])) {
                $words[] = implode('', $temp);
                $temp = [];
                continue;
            }

            $temp[] = $char;
        }

        $words[] = implode('', $temp);

        return array_values(array_filter($words, 'trim'));
    }
}
