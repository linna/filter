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

use DateTime;
use UnexpectedValueException;

/**
 * Check if one date is valid.
 */
class Date extends AbstractDate implements RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'class' => 'Date',
        'full_class' => __CLASS__,
        'alias' => ['date', 'dat', 'd'],
        'args_count' => 1,
        'args_type' => ['string'],
        'has_validate' => true,
        //'has_sanitize' => false
    ];

    /**
     * @var string Valid date.
     */
    private $date;

    /**
     * @var string Date format.
     */
    private $format;

    /**
     * @var string Error message
     */
    private $message = '';

    /**
     * Validate.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $args = func_get_args();

        return $this->concreteValidate($args[0], $args[1]);
    }

    /**
     * Concrete validate.
     *
     * @param string $received
     * @param string $format
     *
     * @return bool
     */
    private function concreteValidate(string $received, string $format): bool
    {
        if ($this->parseDate($received, $format)) {
            return true;
        }

        $this->date = $received;
        $this->format = $format;

        return false;
    }

    /**
     * Parse date.
     *
     * @param string $received
     * @param string $format
     *
     * @return bool
     */
    private function parseDate(string $received, string $format): bool
    {
        $date = date_parse_from_format($format, $received);

        $message = "Received date is not in expected format {$format}";

        if ($date['warning_count']) {
            $this->message = $message;
            return true;
        }

        if ($date['error_count']) {
            $this->message = $message;
            return true;
        }

        return false;
    }

    /**
     * Return DateTime object.
     *
     * @return DateTime
     *
     * @throws UnexpectedValueException
     */
    public function getDateTimeObject(): DateTime
    {
        $dateTimeObject = DateTime::createFromFormat($this->format, $this->date);

        if (!($dateTimeObject instanceof DateTime)) {
            throw new UnexpectedValueException();
        }

        return $dateTimeObject;
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
