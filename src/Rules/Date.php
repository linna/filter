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

use DateTime;

/**
 * Check if one date is valid.
 */
class Date extends AbstractDate implements RuleInterface
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
        'has_sanitize' => true
    ];

    /**
     * @var string Valid date.
     */
    private $date;

    /**
     * @var DateTime Valid date in DateTime object.
     */
    private $dateTimeObject;

    /**
     * @var string Error message
     */
    private $message = '';

    /**
     * Validate.
     *
     * @param string $received
     * @param string $format
     *
     * @return bool
     */
    public function validate(string $received, string $format): bool
    {
        if ($this->parseDate($received, $format)) {
            return true;
        }

        //da spostare nella funzione apposita
        $dateTimeObject = DateTime::createFromFormat($format, $received);

        if (!($dateTimeObject instanceof DateTime)) {
            return true;
        }
        $this->dateTimeObject = $dateTimeObject;
        //spostare fino a qui

        $this->date = $received;


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
     */
    public function getDateTimeObject(): DateTime
    {
        return $this->dateTimeObject;
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
