<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Tests;

use Closure;
use InvalidArgumentException;
use Linna\Filter\Rules\CustomRule;
//use Linna\Filter\Rules\CustomSanitize;
use Linna\Filter\Rules\CustomValidate;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * Custom Rule Test
 */
class CustomRuleTest extends TestCase
{
    /**
     * Wrong alias type provider.
     *
     * @return array
     */
    public function wrongAliasTypeProvider(): array
    {
        return [
            [true],
            [function () {
            }],
            [1.1],
            [1],
            [(object) ['name' => 'foo']],
            ['a'],
        ];
    }

    /**
     * Test alias with wrong argument.
     *
     * @dataProvider wrongAliasTypeProvider
     *
     * @param mixed $argument
     *
     * @return void
     */
    public function testAliasWithWrongType($argument): void
    {
        $this->expectException(TypeError::class);

        (new CustomRule(
            $argument,
            function (string $received): bool {
                if ($received === 'test') {
                    return true;
                }
                return false;
            }
        ));
    }

    /**
     * Test void alias.
     *
     * @return void
     */
    public function testVoidAlias(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Rule test function must have at least one alias.');

        (new CustomRule(
            [],
            function (string $received): bool {
                if ($received === 'test') {
                    return true;
                }
                return false;
            }
        ));
    }

    /**
     * Test closure with no return type.
     *
     * @return void
     */
    public function testClosureWithNoReturnType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Rule test function do not have return type.');

        (new CustomRule(
            ['test'],
            function (string $received) {
                if ($received === 'test') {
                    return true;
                }
                return false;
            }
        ));
    }

    /**
     * Test closure with wrong return type.
     *
     * @return void
     */
    public function testClosureWithWrongReturnType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Rule test function return type must be bool or void.');

        (new CustomRule(
            ['test'],
            function (string $received): int {
                if ($received === 'test') {
                    return 1;
                }
                return 0;
            }
        ));
    }

    /**
     * Test closure with no arguments.
     *
     * @return void
     */
    public function testClosureWithNoArguments(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Rule test function must have at least one argument.');

        (new CustomRule(
            ['test'],
            function (): bool {
                return true;
            }
        ));
    }

    /**
     * Arguments type provider.
     * Test for custom rule arguments reconneissance.
     *
     * @return array
     */
    public function argumentsTypeProvider(): array
    {
        return [
            [function (string $received): bool {
                if ($received === 'test') {
                    return true;
                }
                return false;
            }, 0, []],
            [function (string $received, $value): bool {
                if ($received === $value) {
                    return true;
                }
                return false;
            }, 1, ['string']],
            [function (string $received, int $min): bool {
                if ($received >= $min) {
                    return true;
                }
                return false;
            }, 1, ['number']],
            [function (string $received, float $min, float $max): bool {
                if ($received >= $min && $received <= $max) {
                    return true;
                }
                return false;
            }, 2, ['number', 'number']]
        ];
    }

    /**
     * Test closure argument type.
     *
     * @dataProvider argumentsTypeProvider
     *
     * @param Closure $closure
     * @param int     $argsCount
     * @param array   $argsType
     *
     * @return void
     */
    public function testClosureArgumentsType(Closure $closure, int $argsCount, array $argsType): void
    {
        $instance = new CustomRule(['test'], $closure);
        $concrete = $instance->instance;

        $this->assertInstanceOf(CustomValidate::class, $concrete);
        $this->assertEquals($concrete->config['args_count'], $argsCount);
        $this->assertEquals($concrete->config['args_type'], $argsType);
    }

    /**
     * Test custom validate.
     *
     * @return void
     */
    public function testCustomValidate(): void
    {
        $instance = new CustomRule(
            ['test'],
            function (string $received): bool {
                if ($received === 'test') {
                    return true;
                }
                return false;
            }
        );

        $concrete = $instance->instance;
        $concrete->validate('other test');

        $this->assertSame(true, $concrete->validate('other test'));
        $this->assertSame('Value provided not pass CustomRule (test) test', $concrete->getMessage());

        $concrete->validate('test');

        $this->assertSame(false, $concrete->validate('test'));
        $this->assertSame('Value provided not pass CustomRule (test) test', $concrete->getMessage());
    }

    /**
     * Test Custom Sanitize
     *
     * @return void
     */
    public function testCustomSanitize(): void
    {
        $instance = new CustomRule(
            ['emailtoletters'],
            function (string &$received): void {
                $received = \str_replace('@', ' at ', $received);
                $received = \str_replace('.', ' dot ', $received);
            }
        );

        $concrete = $instance->instance;

        $value = 'test@linna.tools';

        $concrete->sanitize($value);

        $this->assertSame('test at linna dot tools', $value);
        $this->assertSame('Value provided not pass CustomRule (emailtoletters) test', $concrete->getMessage());
    }
}
