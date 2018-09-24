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

use Linna\Filter\Rules\CustomRule;
use PHPUnit\Framework\TestCase;

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
     * @expectedException TypeError
     * @expectedExceptionMessageRegExp /Argument 1 passed to Linna\\Filter\\Rules\\CustomRule::__construct\(\) must be of the type array, (string|boolean|float|integer|object) given/
     *
     * @param mixed $argument
     */
    public function testAliasWithWrongType($argument): void
    {
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
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage  Rule test function must have at least one alias.
     */
    public function testVoidAlias(): void
    {
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
     * Test get message.
     */
    public function testGetMessage(): void
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

        $instance->validate('other test');

        $this->assertSame('Value provided not pass CustomRule (test) test', $instance->getMessage());
    }
}
