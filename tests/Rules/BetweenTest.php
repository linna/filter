<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\Between;
use PHPUnit\Framework\TestCase;

/**
 * Beetwen Test
 */
class BetweenTest extends TestCase
{
    /**
     * Number provider.
     *
     * @return array
     */
    public function numberProvider() : array
    {
        return [
          [0, true],
          [1, false],
          [2, false],
          [3, false],
          [4, false],
          [5, false],
          [6, true]
        ];
    }
    
    /**
     * Test numeric value beetwen.
     *
     * @dataProvider numberProvider
     *
     * @param int $number
     * @param bool $result
     */
    public function testNumericBeetwen(int $number, bool $result)
    {
        $this->assertEquals($result, (new Between())->validate($number, 1, 5));
    }
}
