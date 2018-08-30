<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Linna\Tests;

use DateTime;
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
    public function dateProvider(): array
    {
        return [
            ['2017-11-01', 'Y-m-d', false],
            ['2017-11-01 05', 'Y-m-d H', false],
            ['2017-11-01 05:00', 'Y-m-d H:i', false],
            ['2017-11-01 05:00:30', 'Y-m-d H:i:s', false],
            ['2017-11-01 05:00:30 500000', 'Y-m-d H:i:s u', false],
            ['2017', 'Y-m-d', true],
            ['2017-00', 'Y-m-d', true],
            ['2017-12-00', 'Y-m-d', true],
            ['2017-01-00', 'Y-m-d', true],
            ['2017-00-01', 'Y-m-d', true],
            ['2017-00-00', 'Y-m-d', true],
            ['2017-01-01', 'Y-m-d', false],
            ['2017-01-01 5', 'Y-m-d', true], //trailing data
            ['2017-13', 'Ymd', true],
            ['0-13-11 d', 'Y-m-d', true],
            ['2017-01-32', 'Y-m-d', true],
            ['2017-01-01 25', 'Y-m-d H', true],
            ['2017-01-01 01:61', 'Y-m-d H:i', true],
            ['2017-01-01 01:01:61', 'Y-m-d H:i:s', true],
            ['2017-11-01', 'Y-m', true],
            ['2017-11-01 05', 'Y-m-d', true],
            ['2017-11-01 05:00', 'Y-m-d', true],
            ['2017-11-01 05:00:30', 'Y-m-d', true],
            ['2017-11-01 05:00:30 500', 'Y-m-d', true],
            ['ZZZ', '15', true],
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider dateProvider
     *
     * @param string $date
     * @param string $format
     * @param bool   $result
     */
    public function testValidate(string $date, string $format, bool $result): void
    {
        $instance = new Date();
        $validated = $instance->validate($date, $format);

        $this->assertEquals($result, $validated);

        if ($validated) {
            return;
        }

        $this->assertInstanceOf(DateTime::class, $instance->getDateTimeObject());
        $this->assertSame($date, $instance->getDateTimeObject()->format($format));
    }

    /**
     * Test get message.
     */
    public function testGetMessage(): void
    {
        $instance = new Date();
        $instance->validate('2017-11-01', 'Y-m');

        $this->assertSame('Received date is not in expected format Y-m', $instance->getMessage());
    }
}
