<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\DateMin;
use PHPUnit\Framework\TestCase;

/**
 * Date Min Test
 */
class DateMinTest extends TestCase
{
    
    /**
     * Date min provider.
     *
     * @return array
     */
    public function dateMinProvider() : array
    {
        return [
          ['2018-01', true], //malformed
          ['2018-01-01', true],
          ['2018-01-02', true],
          ['2018-01-03', true],
          ['2018-01-04', false],
          ['2018-01-05', false],
          ['2018-01-06', false]
        ];
    }
    
    /**
     * Test date min.
     *
     * @dataProvider dateMinProvider
     *
     * @param string $min
     * @param bool $result
     */
    public function testDateMin(string $min, bool $result): void
    {
        $this->assertEquals($result, (new DateMin())->validate($min, 'Y-m-d', '2018-01-04'));
    }
}
