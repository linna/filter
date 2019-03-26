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

use Linna\Filter\Lexer;
use Linna\Filter\Parser;
use Linna\Filter\RuleBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Parser Test
 */
class ParserTest extends TestCase
{
    /**
     * Rule provider.
     *
     * @return array
     */
    public function ruleProvider(): array
    {
        return [
            [[ 0 => [ 0 => 'rule', 1 => 'number', 2 => [], ], ], 'rule: number'],
            [[ 0 => [ 0 => 'rule', 1 => 'number', 2 => [], ], 1 => [ 0 => 'rule', 1 => 'numbercompare', 2 => [ 0 => '<', 1 => 25, ], ], ], 'rule: number numbercompare < 25'],
            //[[0 => [0 => 'rule', 1 => 'number', 2 => [], ], 1 => [ 0 => 'rule', 1 => 'min', 2 => [15], ], 2 => [ 0 => 'rule', 1 => 'max', 2 => [30], ], ], 'rule: number min 15 max 30'],
            //[[0 => [0 => 'rule', 1 => 'number', 2 => [], ], 1 => [ 0 => 'rule', 1 => 'between', 2 => [15, 30, ], ], ],'rule: number between 15 30']
        ];
    }

    /**
     * Test if parser work propely.
     *
     * @dataProvider ruleProvider
     *
     * @param array $test
     * @param string $rule
     */
    public function testParser(array $test, string $rule): void
    {
        $parser = new Parser();
        $lexer = new Lexer();

        [$rules, $alias] = RuleBuilder::build();

        $this->assertSame($test, $parser->parse($lexer->tokenize($rule), $rules, $alias));
    }

    /**
     * Unknown rule provider.
     *
     * @return array
     */
    public function unknownRuleProvider(): array
    {
        return [
            ['rule: bnumber', 'bnumber'],
            ['rule: number bmax 30', 'bmax'],
            ['rule: number bmin 15 max 30', 'bmin'],
            ['rule: number bbetween 15 30', 'bbetween'],
            ['rule: bmax 30 number', 'bmax'],
            ['rule: bmin 15 number max 30', 'bmin'],
            ['rule: bbetween 15 30 number', 'bbetween']
        ];
    }

    /**
     * Test parser with unknown rules.
     *
     * @dataProvider unknownRuleProvider
     *
     * @expectedException OutOfBoundsException
     *
     * @param string $rule
     * @param string $test
     */
    public function testParserWithUnknownRules(string $rule, string $test): void
    {
        $parser = new Parser();
        $lexer = new Lexer();

        //thanks php 7.1 for array destructuring!
        [$rules, $alias] = RuleBuilder::build();

        $parser->parse($lexer->tokenize($rule), $rules, $alias);
    }
}
