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

use Closure;

/**
 * Abstract Custom
 */
abstract class AbstractCustom
{
    /**
     * @var array Rule properties
     */
    public $config = [
        'full_class' => '',
        'alias' => [],
        'args_count' => 0,
        'args_type' => [],
    ];

    /**
     * @var string Error message
     */
    protected $message = '';

    /**
     * @var Closure Rule custom function for validate or sanitize.
     */
    protected $callback;

    /**
     * Class Constructor.
     *
     * @param Closure $callback
     * @param array   $config
     * @param string  $message
     */
    public function __construct(Closure $callback, array $config, string $message)
    {
        $this->callback = $callback;
        $this->config = $config;
        $this->message = $message;
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
