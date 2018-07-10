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
            ['A', '>', 'B', true],
            ['B', '>', 'B', true],
            ['C', '>', 'B', false],

            ['A', '<', 'B', false],
            ['B', '<', 'B', true],
            ['C', '<', 'B', true],

            ['A', '>=', 'B', true],
            ['B', '>=', 'B', false],
            ['C', '>=', 'B', false],

            ['A', '<=', 'B', false],
            ['B', '<=', 'B', false],
            ['C', '<=', 'B', true],

            ['A', '=', 'B', true],
            ['B', '=', 'B', false],
            ['C', '=', 'B', true],

            ['A', '!=', 'B', false],
            ['B', '!=', 'B', true],
            ['C', '!=', 'B', false],

        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider stringProvider
     *
     * @param string $received
     * @param string $operator
     * @param string $compare
     * @param bool $result
     */
    public function testValidate(string $received, string $operator, string $compare, bool $result): void
    {
        $this->assertSame($result, (new StringCompare())->validate($received, $operator, $compare));
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
