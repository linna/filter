<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

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
            ['A', '=', '2', true],
            
            [1, '>', 2, true],
            [2, '>', 2, true],
            [3, '>', 2, false],
            
            [1, '<', 2, false],
            [2, '<', 2, true],
            [3, '<', 2, true],
            
            [1, '>=', 2, true],
            [2, '>=', 2, false],
            [3, '>=', 2, false],
            
            [1, '<=', 2, false],
            [2, '<=', 2, false],
            [3, '<=', 2, true],
            
            [1, '=', 2, true],
            [2, '=', 2, false],
            [3, '=', 2, true],
            
            [1, '=', '2', true],
            [2, '=', '2', true],
            [3, '=', '2', true]
        ];
    }
    
    /**
     * Test validate.
     *
     * @dataProvider numberProvider
     *
     * @param mixed $received
     * @param string $operator
     * @param mixed $compare
     * @param bool $result
     */
    public function testValidate($received, string $operator, $compare, bool $result): void
    {
        $instance = new NumberCompare();
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
        (new NumberCompare())->validate('1', '!', '1');
    }
}
