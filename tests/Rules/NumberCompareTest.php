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

use Linna\Filter\Rules\NumberCompare;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * Number Compare Test
 */
class NumberCompareTest extends TestCase
{
    /**
     * Number provider.
     *
     * @return array
     */
    public function numberProvider(): array
    {
        return [
            [1, '>', 2, true],
            [2, '>', 2, true],
            [3, '>', 2, false],

            [1.0, '>', 2, true],
            [2.0, '>', 2, true],
            [3.0, '>', 2, false],

            [1.1, '>', 2.1, true],
            [2.1, '>', 2.1, true],
            [3.1, '>', 2.1, false],

            [1, '<', 2, false],
            [2, '<', 2, true],
            [3, '<', 2, true],

            [1.1, '<', 2.1, false],
            [2.1, '<', 2.1, true],
            [3.1, '<', 2.1, true],

            [1, '>=', 2, true],
            [2, '>=', 2, false],
            [3, '>=', 2, false],

            [1.1, '>=', 2.1, true],
            [2.1, '>=', 2.1, false],
            [3.1, '>=', 2.1, false],

            [1, '<=', 2, false],
            [2, '<=', 2, false],
            [3, '<=', 2, true],

            [1, '=', 2, true],
            [2, '=', 2, false],
            [3, '=', 2, true],

            [1.1, '=', 2.1, true],
            [2.1, '=', 2.1, false],
            [3.1, '=', 2.1, true],

            [1, '=', '2', true],
            [2, '=', '2', false],
            [3, '=', '2', true],

            ['1', '=', '2', true],
            ['2', '=', '2', false],
            ['3', '=', '2', true],

            ['1', '=', 2, true],
            ['2', '=', 2, false],
            ['3', '=', 2, true],

            ['A', '=', 2, true],
            [1, '=', 'A', true],
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider numberProvider
     *
     * @param int|float $received
     * @param string    $operator
     * @param int|float $compare
     * @param bool      $result
     *
     * @return void
     */
    public function testValidate($received, string $operator, $compare, bool $result): void
    {
        $this->assertSame($result, (new NumberCompare())->validate($received, $operator, $compare));
    }

    /**
     * Test unknown comparison operator.
     *
     * @return void
     */
    public function testUnknownOperator(): void
    {
        $this->expectException(UnexpectedValueException::class);

        (new NumberCompare())->validate(1, '!', 1);
    }

    /**
     * Test get message.
     *
     * @return void
     */
    public function testGetMessage(): void
    {
        $instance = new NumberCompare();
        $instance->validate('1', '=', '2');

        $this->assertSame('Received number is not = 2', $instance->getMessage());
    }
}
