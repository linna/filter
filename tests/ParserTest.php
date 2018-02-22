<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

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
            [[0 => [0 => 'rule', 1 => 'number', 2 => ['class' => 'Number', 'keyword' => 'number', 'args_count' => 0, 'args_type' => [ ],], 3 => true, ], ], 'rule: number'],
            [[0 => [0 => 'rule', 1 => 'number', 2 => ['class' => 'Number', 'keyword' => 'number', 'args_count' => 0, 'args_type' => [ ],], 3 => true, ], 1 => [ 0 => 'rule', 1 => 'max', 2 => [ 'class' => 'Max', 'keyword' => 'max', 'args_count' => 1, 'args_type' => [ 0 => 'number', ], ], 3 => 30, ], ], 'rule: number max 30'],
            [[0 => [0 => 'rule', 1 => 'number', 2 => ['class' => 'Number', 'keyword' => 'number', 'args_count' => 0, 'args_type' => [ ],], 3 => true, ], 1 => [ 0 => 'rule', 1 => 'min', 2 => [ 'class' => 'Min', 'keyword' => 'min', 'args_count' => 1, 'args_type' => [ 0 => 'number', ], ], 3 => 15, ], 2 => [ 0 => 'rule', 1 => 'max', 2 => [ 'class' => 'Max', 'keyword' => 'max', 'args_count' => 1, 'args_type' => [ 0 => 'number', ], ], 3 => 30, ], ], 'rule: number min 15 max 30'],
            [[0 => [0 => 'rule', 1 => 'number', 2 => ['class' => 'Number', 'keyword' => 'number', 'args_count' => 0, 'args_type' => [ ],], 3 => true, ], 1 => [ 0 => 'rule', 1 => 'between', 2 => [ 'class' => 'Between', 'keyword' => 'between', 'args_count' => 2, 'args_type' => [ 0 => 'number', 1 => 'number', ], ], 3 => [ 0 => 15, 1 => 30, ], ], ],'rule: number between 15 30']
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
        
        $this->assertEquals($test, $parser->parse(Lexer::tokenize($rule), RuleBuilder::build()));
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
        $parser->parse(Lexer::tokenize($rule), RuleBuilder::build());
    }
}
