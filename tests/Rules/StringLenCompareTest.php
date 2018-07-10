<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\StringLenCompare;
use PHPUnit\Framework\TestCase;

/**
 * String Length Compare Test
 */
class StringLenCompareTest extends TestCase
{
    /**
     * String provider.
     *
     * @return array
     */
    public function stringProvider(): array
    {
        return [
            ['A', '>', 2, true],
            ['AA', '>', 2, true],
            ['AAA', '>', 2, false],

            ['A', '<', 2, false],
            ['AA', '<', 2, true],
            ['AAA', '<', 2, true],

            ['A', '>=', 2, true],
            ['AA', '>=', 2, false],
            ['AAA', '>=', 2, false],

            ['A', '<=', 2, false],
            ['AA', '<=', 2, false],
            ['AAA', '<=', 2, true],

            ['A', '=', 2, true],
            ['AA', '=', 2, false],
            ['AAA', '=', 2, true],

            ['A', '!=', 2, false],
            ['AA', '!=', 2, true],
            ['AAA', '!=', 2, false],
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider stringProvider
     *
     * @param string $received
     * @param string $operator
     * @param int $compare
     * @param bool $result
     */
    public function testValidate(string $received, string $operator, int $compare, bool $result): void
    {
        $this->assertEquals($result, (new StringLenCompare())->validate($received, $operator, $compare));
    }

    /**
     * Test unknown comparison operator.
     *
     * @expectedException UnexpectedValueException
     */
    public function testUnknownOperator(): void
    {
        (new StringLenCompare())->validate('1', '!', 1);
    }
}
