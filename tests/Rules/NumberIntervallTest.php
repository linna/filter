<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\NumberIntervall;
use PHPUnit\Framework\TestCase;

/**
 * Number Intervall Test
 */
class NumberIntervallTest extends TestCase
{
    /**
     * Number provider.
     *
     * @return array
     */
    public function numberProvider(): array
    {
        return [
            ['A', '<>', 2, 4, true],

            [1, '<>', 2, 4, false],
            [2, '<>', 2, 4, true],
            [3, '<>', 2, 4, true],
            [4, '<>', 2, 4, true],
            [5, '<>', 2, 4, false],

            [1, '<=>', 2, 4, false],
            [2, '<=>', 2, 4, false],
            [3, '<=>', 2, 4, true],
            [4, '<=>', 2, 4, false],
            [5, '<=>', 2, 4, false],

            [1, '><', 2, 4, true],
            [2, '><', 2, 4, true],
            [3, '><', 2, 4, false],
            [4, '><', 2, 4, true],
            [5, '><', 2, 4, true],

            [1, '>=<', 2, 4, true],
            [2, '>=<', 2, 4, false],
            [3, '>=<', 2, 4, false],
            [4, '>=<', 2, 4, false],
            [5, '>=<', 2, 4, true]
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider numberProvider
     *
     * @param mixed $received
     * @param string $operator
     * @param mixed $min
     * @param mixed $max
     * @param bool $result
     */
    public function testValidate($received, string $operator, $min, $max, bool $result): void
    {
        $instance = new NumberIntervall();
        $validated = $instance->validate($received, $operator, $min, $max);

        $this->assertEquals($result, $validated);
    }

    /**
     * Test unknown comparison operator.
     *
     * @expectedException UnexpectedValueException
     */
    public function testUnknownOperator(): void
    {
        (new NumberIntervall())->validate('1', '!', '2', '4');
    }
}
