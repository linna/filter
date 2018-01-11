<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\DateMax;
use PHPUnit\Framework\TestCase;

/**
 * Date Max Test
 */
class DateMaxTest extends TestCase
{
    
    /**
     * Date max provider.
     *
     * @return array
     */
    public function dateMaxProvider() : array
    {
        return [
          ['2018-01-01', false],
          ['2018-01-02', false],
          ['2018-01-03', false],
          ['2018-01-04', false],
          ['2018-01-05', true],
          ['2018-01-06', true]
        ];
    }
    
    /**
     * Test date max.
     *
     * @dataProvider dateMaxProvider
     *
     * @param string $max
     * @param bool $result
     */
    public function testDateMin(string $max, bool $result)
    {
        $this->assertEquals($result, (new DateMax())->validate($max, 'Y-m-d', '2018-01-04'));
    }
}
