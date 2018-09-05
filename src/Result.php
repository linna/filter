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

abstract class Result
{
    /**
     * @var array Sanitized data.
     */
    private $data = [];

    /**
     * @var array Error messages.
     */
    private $message = [];

    /**
    * @var int Occurred errors.
    */
    private $error = 0;

    /**
     * Class Constructor.
     *
     * @param array $data
     * @param array $message
     * @param int   $error
     */
    public function __construct(array $data, array $message, int $error)
    {
        $this->data = $data;
        $this->message = $message;
        $this->error = $error;
    }

    /**
     * Return sanitized data.
     *
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Return error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return $this->message;
    }

    /**
     * Return the number of occurred errors.
     *
     * @return int
     */
    public function errors(): int
    {
        return $this->error;
    }
}
