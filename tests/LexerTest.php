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
use PHPUnit\Framework\TestCase;

/**
 * Filter Test
 */
class LexerTest extends TestCase
{
    /**
     * Rules and data provider.
     *
     * @return array
     */
    public function rulesProvider() : array
    {
        return [
          ['field: rule param',['field','rule','param']],
          ['field: rule \'p a r a m\'',['field','rule','p a r a m']],
        ];
    }
    
    /**
     * Test Filter.
     *
     * @dataProvider rulesProvider
     *
     * @param string $data
     * @param array $expected
     */
    public function testFilterOne(string $data, array $expected): void
    {
        $this->assertEquals(Lexer::tokenize($data), $expected);
    }
}
