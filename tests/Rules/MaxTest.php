<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\Max;
use PHPUnit\Framework\TestCase;

/**
 * Max Test
 */
class MaxTest extends TestCase
{
    /**
     * Numeric max provider.
     *
     * @return array
     */
    public function numericMaxProvider() : array
    {
        return [
          [10, false],
          [11, false],
          [12, false],
          [13, true],
          [14, true],
          [15, true]
        ];
    }
    
    /**
     * Test numeric max.
     *
     * @dataProvider numericMaxProvider
     *
     * @param int $max
     * @param bool $result
     */
    public function testNumericMax(int $max, bool $result)
    {
        $this->assertEquals($result, (new Max())->validate($max, 12));
    }
}
