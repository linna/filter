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
     */
    public function testValidate($received, string $operator, $compare, bool $result): void
    {
        $this->assertSame($result, (new NumberCompare())->validate($received, $operator, $compare));
    }

    /**
     * Test unknown comparison operator.
     *
     * @expectedException UnexpectedValueException
     */
    public function testUnknownOperator(): void
    {
        (new NumberCompare())->validate(1, '!', 1);
    }
}
