<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\StringCompare;
use PHPUnit\Framework\TestCase;

/**
 * String Compare Test
 */
class StringCompareTest extends TestCase
{
    /**
     * String provider.
     *
     * @return array
     */
    public function stringProvider(): array
    {
        return [
            [2, '=', 2, true],

            ['A', 'len>', 2, true],
            ['AA', 'len>', 2, true],
            ['AAA', 'len>', 2, false],

            ['A', 'len<', 2, false],
            ['AA', 'len<', 2, true],
            ['AAA', 'len<', 2, true],

            ['A', 'len>=', 2, true],
            ['AA', 'len>=', 2, false],
            ['AAA', 'len>=', 2, false],

            ['A', 'len<=', 2, false],
            ['AA', 'len<=', 2, false],
            ['AAA', 'len<=', 2, true],

            ['A', 'len=', 2, true],
            ['AA', 'len=', 2, false],
            ['AAA', 'len=', 2, true],

            ['A', '=', 'AA', true],
            ['AA', '=', 'AA', false],
            ['AAA', '=', 'AA', true]
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider stringProvider
     *
     * @param mixed $received
     * @param string $operator
     * @param mixed $compare
     * @param bool $result
     */
    public function testValidate($received, string $operator, $compare, bool $result): void
    {
        $instance = new StringCompare();
        $validated = $instance->validate($received, $operator, $compare);

        $this->assertEquals($result, $validated);
    }

    /**
     * Test unknown comparison operator.
     *
     * @expectedException UnexpectedValueException
     */
    public function testUnknownOperator(): void
    {
        (new StringCompare())->validate('1', '!', '1');
    }
}
