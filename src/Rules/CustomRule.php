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
class CustomRule implements RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public $config = [
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
     * @var string Error message
     */
    private $message = '';

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

        $this->message = "Value provided not pass CustomRule ({$alias[0]}) test";
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

        if (!$reflection->hasReturnType()) {
            throw new InvalidArgumentException('Rule test function do not have return type.');
        }

        if (!in_array((string) $reflection->getReturnType(), ['bool', 'void'])) {
            throw new InvalidArgumentException('Rule test function return type must be bool or void.');
        }

        $this->parseClosureParams($reflection);

        $this->callback = $test;
    }

    private function parseClosureParams(ReflectionFunction &$reflection): void
    {
        $parameters = $reflection->getParameters();

        if (count($parameters) === 0) {
            throw new InvalidArgumentException('Rule test function must have at least one parameter.');
        }

        //remove firs param, the received value
        array_shift($parameters);

        $this->config['args_count'] = count($parameters);

        foreach ($parameters as $param) {
            if ($param->hasType()) {
                if (in_array((string) $param->getType(), ['int', 'float'])) {
                    $this->config['args_type'][] = 'number';
                }
            }

            $this->config['args_type'][] = 'string';
        }
    }

    /**
     * Validate.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $args = func_get_args();

        return !call_user_func_array($this->callback, $args);
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
