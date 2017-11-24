<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\Min;
use PHPUnit\Framework\TestCase;

/**
 * Min Test
 */
class MinTest extends TestCase
{
    
    /**
     * Numeric min provider.
     *
     * @return array
     */
    public function numericMinProvider() : array
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
     * Test numeric value min.
     *
     * @dataProvider numericMinProvider
     *
     * @param int $min
     * @param bool $result
     */
    public function testNumericValueMax(int $min, bool $result)
    {
        $this->assertEquals($result, (new Min())->validate(12, $min));
    }
}
