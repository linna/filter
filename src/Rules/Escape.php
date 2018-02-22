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
     * @var array Arguments expected.
     */
    private $arguments = [];
    
    /**
     * Sanitize.
     *
     * @param mixed $value
     */
    public function sanitize(&$value): void
    {
        $value = $this->htmlEscape($value);
    }
    
    /**
     * Return numerical part of the HTML encoding of the Unicode character.
     *
     * @param string $char
     * @return int
     */
    private function ordutf8(string $char): int
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
    private function htmlEscape(string $string): string
    {
        $chars = preg_split('//u', $string, 0, PREG_SPLIT_NO_EMPTY);
        $escaped = '';

        $permitted = [
            32,48,49,50,51,52,53,54,55,56,57,65,66,67,68,
            69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,
            84,85,86,87,88,89,90,97,98,99,100,101,102,103,
            104,105,106,107,108,109,110,111,112,113,114,
            115,116,117,118,119,120,121,122
        ];

        foreach ($chars as $char) {
            $ord = $this->ordutf8($char);

            if (!in_array($ord, $permitted)) {
                $escaped .= "&#{$ord};";
                continue;
            }

            $escaped .= $char;
        }

        return $escaped;
    }
}
