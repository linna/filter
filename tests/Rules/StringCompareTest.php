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
            [2, '=', 'A', true],

            ['A', 'len>', 'AA', true],
            ['AA', 'len>', 'AA', true],
            ['AAA', 'len>', 'AA', false],

            ['A', 'len<', 'AA', false],
            ['AA', 'len<', 'AA', true],
            ['AAA', 'len<', 'AA', true],

            ['A', 'len>=', 'AA', true],
            ['AA', 'len>=', 'AA', false],
            ['AAA', 'len>=', 'AA', false],

            ['A', 'len<=', 'AA', false],
            ['AA', 'len<=', 'AA', false],
            ['AAA', 'len<=', 'AA', true],

            ['A', 'len=', 'AA', true],
            ['AA', 'len=', 'AA', false],
            ['AAA', 'len=', 'AA', true],

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
