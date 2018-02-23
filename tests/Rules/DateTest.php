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
     * Test validate.
     *
     * @dataProvider dateProvider
     *
     * @param string $date
     * @param string $format
     * @param bool $result
     */
    public function testValidate(string $date, string $format, bool $result): void
    {
        $this->assertEquals($result, (new Date())->validate($date, $format));
    }

    /**
     * Test sanitize.
     *
     * @dataProvider dateProvider
     *
     * @param string $date
     * @param string $format
     * @param bool $result
     */
    public function testSanitize(string $date, string $format, bool $result): void
    {
        $instance = new Date();
        $validated = $instance->validate($date, $format);

        if (!$validated) {
            $instance->sanitize($date);
            $this->assertInstanceOf(DateTime::class, $date);
        }

        $this->assertEquals($result, $validated);
    }
}
