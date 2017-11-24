<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
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
          [10, true],
          [11, true],
          [12, false],
          [13, false],
          [14, false],
          [15, false]
        ];
    }
    
    /**
     * Test numeric value max.
     *
     * @dataProvider numericMaxProvider
     *
     * @param int $max
     * @param bool $result
     */
    public function testNumericValueMax(int $max, bool $result)
    {
        $this->assertEquals($result, (new Max())->validate(12, $max));
    }
}
