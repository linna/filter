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

use Linna\Filter\Rules\StringLenCompare;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

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
     * @param int    $compare
     * @param bool   $result
     *
     * @return void
     */
    public function testValidate(string $received, string $operator, int $compare, bool $result): void
    {
        $this->assertEquals($result, (new StringLenCompare())->validate($received, $operator, $compare));
    }

    /**
     * Test unknown comparison operator.
     *
     * @return void
     */
    public function testUnknownOperator(): void
    {
        $this->expectException(UnexpectedValueException::class);

        (new StringLenCompare())->validate('1', '!', 1);
    }

    /**
     * Test get message for zero length string.
     *
     * @return void
     */
    public function testGetMessageForZeroLength(): void
    {
        $instance = new StringLenCompare();
        $instance->validate('A', '>', 2);

        $this->assertSame('Received string length is not > of 2', $instance->getMessage());
    }
}
