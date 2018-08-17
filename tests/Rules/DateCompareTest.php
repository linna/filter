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
use Linna\Filter\Rules\DateCompare;
use PHPUnit\Framework\TestCase;

/**
 * Date Compare Test
 */
class DateCompareTest extends TestCase
{
    /**
     * Date provider.
     *
     * @return array
     */
    public function dateProvider(): array
    {
        return [
            ['2018-01', '>', 'Y-m-d', '2018-01-01', true], //malformed

            ['2018-01-04', '>', 'Y-m-d', '2018-01-05', true],
            ['2018-01-05', '>', 'Y-m-d', '2018-01-05', true],
            ['2018-01-06', '>', 'Y-m-d', '2018-01-05', false],

            ['2018-01-05 11:59:59', '>', 'Y-m-d H:i:s', '2018-01-05 12:00:00', true],
            ['2018-01-05 12:00:00', '>', 'Y-m-d H:i:s', '2018-01-05 12:00:00', true],
            ['2018-01-05 12:00:01', '>', 'Y-m-d H:i:s', '2018-01-05 12:00:00', false],

            ['2018-01-04', '<', 'Y-m-d', '2018-01-05', false],
            ['2018-01-05', '<', 'Y-m-d', '2018-01-05', true],
            ['2018-01-06', '<', 'Y-m-d', '2018-01-05', true],

            ['2018-01-05 11:59:59', '<', 'Y-m-d H:i:s', '2018-01-05 12:00:00', false],
            ['2018-01-05 12:00:00', '<', 'Y-m-d H:i:s', '2018-01-05 12:00:00', true],
            ['2018-01-05 12:00:01', '<', 'Y-m-d H:i:s', '2018-01-05 12:00:00', true],

            ['2018-01-04', '>=', 'Y-m-d', '2018-01-05', true],
            ['2018-01-05', '>=', 'Y-m-d', '2018-01-05', false],
            ['2018-01-06', '>=', 'Y-m-d', '2018-01-05', false],

            ['2018-01-05 11:59:59', '>=', 'Y-m-d H:i:s', '2018-01-05 12:00:00', true],
            ['2018-01-05 12:00:00', '>=', 'Y-m-d H:i:s', '2018-01-05 12:00:00', false],
            ['2018-01-05 12:00:01', '>=', 'Y-m-d H:i:s', '2018-01-05 12:00:00', false],

            ['2018-01-04', '<=', 'Y-m-d', '2018-01-05', false],
            ['2018-01-05', '<=', 'Y-m-d', '2018-01-05', false],
            ['2018-01-06', '<=', 'Y-m-d', '2018-01-05', true],

            ['2018-01-05 11:59:59', '<=', 'Y-m-d H:i:s', '2018-01-05 12:00:00', false],
            ['2018-01-05 12:00:00', '<=', 'Y-m-d H:i:s', '2018-01-05 12:00:00', false],
            ['2018-01-05 12:00:01', '<=', 'Y-m-d H:i:s', '2018-01-05 12:00:00', true],

            ['2018-01-04', '=', 'Y-m-d', '2018-01-05', true],
            ['2018-01-05', '=', 'Y-m-d', '2018-01-05', false],
            ['2018-01-06', '=', 'Y-m-d', '2018-01-05', true],

            ['2018-01-05 11:59:59', '=', 'Y-m-d H:i:s', '2018-01-05 12:00:00', true],
            ['2018-01-05 12:00:00', '=', 'Y-m-d H:i:s', '2018-01-05 12:00:00', false],
            ['2018-01-05 12:00:01', '=', 'Y-m-d H:i:s', '2018-01-05 12:00:00', true],
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider dateProvider
     *
     * @param string $received
     * @param string $operator
     * @param string $format
     * @param string $compare
     * @param bool $result
     *
     * @return void
     */
    public function testValidate(string $received, string $operator, string $format, string $compare, bool $result): void
    {
        $instance = new DateCompare();
        $validated = $instance->validate($received, $operator, $format, $compare);

        $this->assertEquals($result, $validated);

        if ($validated) {
            return;
        }

        $this->assertInstanceOf(DateTime::class, $instance->getDateTimeObject());
        $this->assertSame($received, $instance->getDateTimeObject()->format($format));
    }

    /**
     * Test unknown comparison operator.
     *
     * @expectedException UnexpectedValueException
     */
    public function testUnknownOperator(): void
    {
        (new DateCompare())->validate('2018-01-04', '!', 'Y-m-d', '2018-01-05');
    }

    /**
     * Test date without time.
     */
    public function testDateWithoutTime(): void
    {
        $instance = new DateCompare();
        $instance->validate('2018-01-05', '=', 'Y-m-d', '2018-01-05');

        $this->assertSame('20180105000000', $instance->getDateTimeObject()->format('YmdHis'));
    }
}
