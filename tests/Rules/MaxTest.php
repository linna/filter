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
     * Number provider.
     *
     * @return array
     */
    public function numberProvider() : array
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
     * Test validate.
     *
     * @dataProvider numberProvider
     *
     * @param int $number
     * @param bool $result
     */
    public function testValidate(int $number, bool $result): void
    {
        $this->assertEquals($result, (new Max())->validate($number, 12));
    }
}
