<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Filter\Rules;

/**
 * Return html entities.
 */
class Escape implements RuleSanitizeInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'full_class' => __CLASS__,
        'alias' => ['escape', 'escp', 'es'],
        'args_count' => 0,
        'args_type' => []
    ];

    /**
     * @var array Forbidden special chars in interger format.
     */
    private $special = [
        33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47,
        58, 59, 60, 61, 62, 63, 64,
        91, 92, 93, 94, 95, 96,
        123, 124, 125, 126
    ];

    /**
     * @var string Error message
     */
    private $message = '';

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
     * Convert char to html entities.
     *
     * @param string $string
     *
     * @return string
     */
    private function htmlEscape(string $string): string
    {
        $chars = \preg_split('//u', $string, \strlen($string), PREG_SPLIT_NO_EMPTY);
        $escaped = '';

        if ($chars === false) {
            return $escaped;
        }

        foreach ($chars as $char) {
            $ord = \ord($char);

            if (\in_array($ord, $this->special)) {
                $escaped .= "&#{$ord};";
                continue;
            }

            $escaped .= $char;
        }

        return $escaped;
    }

    /**
     * Return error message.
     *
     * @return string Error message
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
