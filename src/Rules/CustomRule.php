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
use InvalidArgumentException;
use ReflectionFunction;

/**
 * Add custom rule to filter.
 */
class CustomRule
{
    /**
     * @var RuleInterface Instance of concrete custom rule.
     */
    public $instance;

    /**
     * @var array Rule properties
     */
    private $config = [
        'full_class' => __CLASS__,
        'alias' => [],
        'args_count' => 0,
        'args_type' => [],
    ];

    /**
     * @var callable Rule custom function for validate method.
     */
    private $callback;

    /**
     * @var bool Filter type: false validate, true sanitize.
     */
    private $sanitize = false;

    /**
     * Class Constructor.
     *
     * @param array    $alias Rule aliases
     * @param Closure  $test  Rule custom function for validation
     */
    public function __construct(array $alias, Closure $test)
    {
        $this->parseAlias($alias);
        $this->parseClosure($test);

        $message = "Value provided not pass CustomRule ({$alias[0]}) test";

        $this->instance = new CustomValidate($test, $this->config, $message);

        if ($this->sanitize) {
            $this->instance = new CustomSanitize($test, $this->config, $message);
        }
    }

    /**
     * Parse alias
     *
     * @param array $alias
     *
     * @throws InvalidArgumentException if no alias provided for rule.
     */
    private function parseAlias(array $alias): void
    {
        if (count($alias) === 0) {
            throw new InvalidArgumentException('Rule test function must have at least one alias.');
        }

        $this->config['alias'] = array_map('strtolower', $alias);
    }

    /**
     * Parse test function for validate method.
     *
     * @param Closure $test
     *
     * @throws InvalidArgumentException if test function no dot have return type, if
     *                                  return type not bool or not void, if function do not
     *                                  have at least one parameter.
     */
    private function parseClosure(Closure $test): void
    {
        $reflection = new ReflectionFunction($test);
        $parameters = $reflection->getParameters();

        if (!$reflection->hasReturnType()) {
            throw new InvalidArgumentException('Rule test function do not have return type.');
        }

        if (!in_array((string) $reflection->getReturnType(), ['bool', 'void'])) {
            throw new InvalidArgumentException('Rule test function return type must be bool or void.');
        }

        if (count($parameters) === 0) {
            throw new InvalidArgumentException('Rule test function must have at least one argument.');
        }

        $this->parseClosureArgs($parameters);

        $this->callback = $test;
    }

    /**
     * Parse test function arguments.
     *
     * @param array $parameters
     */
    private function parseClosureArgs(array $parameters): void
    {
        //check for sanitizing
        if (($first = $parameters[0]) && $first->isPassedByReference()) {
            $this->sanitize = true;
        }

        //remove firs param, the received value
        array_shift($parameters);

        $this->config['args_count'] = count($parameters);

        foreach ($parameters as $param) {
            if (in_array((string) $param->getType(), ['int', 'float'])) {
                $this->config['args_type'][] = 'number';
                continue;
            }

            $this->config['args_type'][] = 'string';
        }
    }
}
