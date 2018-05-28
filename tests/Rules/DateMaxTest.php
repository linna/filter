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
     * Date provider.
     *
     * @return array
     */
    public function dateProvider() : array
    {
        return [
          ['2018-01', true], //malformed
          ['2018-01-01', false],
          ['2018-01-02', false],
          ['2018-01-03', false],
          ['2018-01-04', false],
          ['2018-01-05', true],
          ['2018-01-06', true]
        ];
    }
    
    /**
     * Test validate.
     *
     * @dataProvider dateProvider
     *
     * @param string $date
     * @param bool $result
     */
    public function testValidate(string $date, bool $result): void
    {
        $instance = new DateMax();

        $this->assertEquals($result, $instance->validate($date, 'Y-m-d', '2018-01-04'));

        if ($instance->validate($date, 'Y-m-d', '2018-01-04')){
            return;
        }

        $instance->sanitize($date);
        $this->assertInstanceOf(DateTime::class, $date);
    }
}
