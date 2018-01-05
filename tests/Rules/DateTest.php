<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\Date;
use PHPUnit\Framework\TestCase;

/**
 * Date Test
 */
class DateTest extends TestCase
{
    /**
     * Date provider.
     *
     * @return array
     */
    public function dateProvider() : array
    {
        return [
          ['2017-11-01', 'Y-m-d', false],
          ['2017-13-01', 'Y-m-d', false],
          ['2017-11', 'Y-m', false],
          ['20171101', 'Ymd', false],
          ['2017-11-01', 'Ymd', true],
          ['2017-13-01', 'Ymd', true],
          ['2017-11', 'Ym', true],
          ['20171101', 'Y-m-d', true]
        ];
    }
    
    /**
     * Test date.
     *
     * @dataProvider dateProvider
     *
     * @param string $date
     * @param bool $result
     */
    public function testDate(string $date, string $format, bool $result)
    {
        $this->assertEquals($result, (new Date())->validate($date, $format));
    }
}
