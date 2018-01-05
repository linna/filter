<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Filter\Rules;

/**
 * Return html entities.
 */
class Escape
{
    /**
     * Sanitize.
     *
     * @param mixed $value
     */
    public function sanitize(&$value)
    {
        $value = $this->htmlEscape($value);
    }
    
    /**
     * Return numerical part of the HTML encoding of the Unicode character.
     *
     * @param string $char
     * @return int
     */
    private function ordutf8(string $char) : int
    {
        $code = ord(substr($char, 0, 1));

        if ($code > 239) {
            return ((ord(substr($char, 1, 1)) - 128) *
                    64 + ord(substr($char, 2, 1)) - 128) *
                    64 + ord(substr($char, 3, 1)) - 128;
        }

        if ($code > 223) {
            return (($code - 224) * 64 + ord(substr($char, 1, 1)) - 128)
                    * 64 + ord(substr($char, 2, 1)) - 128;
        }

        if ($code > 127) {
            return ($code - 192) * 64 + ord(substr($char, 1, 1)) - 128;
        }

        return $code;
    }

    /**
     * Convert char to html entities.
     *
     * @param string $string
     * @return string
     */
    private function htmlEscape(string $string) : string
    {
        $chars = preg_split('//u', $string, 0, PREG_SPLIT_NO_EMPTY);
        $escaped = '';

        foreach ($chars as $char) {
            $ord = $this->ordutf8($char);

            if (
                ($ord > 32 && $ord < 48) ||
                ($ord > 57 && $ord < 65) ||
                ($ord > 90 && $ord < 97) ||
                ($ord > 122)
            ) {
                $escaped .= "&#{$ord};";
                continue;
            }

            $escaped .= $char;
        }

        return $escaped;
    }
}
