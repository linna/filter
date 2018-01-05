<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
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
          [10, true],
          [11, true],
          [12, false],
          [13, false],
          [14, false],
          [15, false]
        ];
    }
    
    /**
     * Test numeric min.
     *
     * @dataProvider numericMinProvider
     *
     * @param int $min
     * @param bool $result
     */
    public function testNumericMin(int $min, bool $result)
    {
        $this->assertEquals($result, (new Min())->validate($min, 12));
    }
}
