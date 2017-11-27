<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\RuleInterpreter;
use PHPUnit\Framework\TestCase;

/**
 * Rule Interpreter Test
 */
class RuleInterpreterTest extends TestCase
{
    /**
     * Rule Provider.
     *
     * @return array
     */
    public function ruleProvider(): array
    {
        return [
            ['age min 18', [['age', 'min', ['Min', 'number', 1], 18]]],
            ['age min 18.0', [['age', 'min', ['Min', 'number', 1], 18]]],
            ['age min 18 max 25', [['age', 'min', ['Min', 'number', 1], 18],['age', 'max', ['Max', 'number', 1], 25]]],
            ['age between 18 25', [['age', 'between', ['Between', 'number', 2], [18, 25]]]],
            ['age between 18.5 25.5', [['age', 'between', ['Between', 'number', 2], [18.5, 25.5]]]],
            ['age required max 25', [['age', 'required', ['Required', 'boolean', 0], true],['age', 'max', ['Max', 'number', 1], 25]]],
        ];
    }
    
    /**
     * Test rule.
     *
     * @dataProvider ruleProvider
     *
     * @param string $rule
     * @param array $expected
     */
    public function testRule(string $rule, array $expected)
    {
        $this->assertSame($expected, (new RuleInterpreter($rule))->get());
    }
}
