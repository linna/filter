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

use InvalidArgumentException;
use Linna\Filter\Rules\IPRange;
use PHPUnit\Framework\TestCase;

/**
 * IPv6Range Test
 */
class IPv6RangeTest extends TestCase
{
    /**
     * valid IPv6 range provider.
     *
     * @return array
     */
    public function validIPv6RangeProvider(): array
    {
        return [
            ['2001:abcd::0008', '2001:abcd::0010/125', true],
            ['2001:abcd::0009', '2001:abcd::0010/125', true],
            ['2001:abcd::0010', '2001:abcd::0010/125', false],
            ['2001:abcd::0011', '2001:abcd::0010/125', false],
            ['2001:abcd::0012', '2001:abcd::0010/125', false],
            ['2001:abcd::0013', '2001:abcd::0010/125', false],
            ['2001:abcd::0014', '2001:abcd::0010/125', false],
            ['2001:abcd::0015', '2001:abcd::0010/125', false],
            ['2001:abcd::0016', '2001:abcd::0010/125', false],
            ['2001:abcd::0017', '2001:abcd::0010/125', false],
            ['2001:abcd::0018', '2001:abcd::0010/125', true],
            ['2001:abcd::0019', '2001:abcd::0010/125', true],
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider validIPv6RangeProvider
     *
     * @param string $received
     * @param string $range
     * @param bool   $result
     *
     * @return void
     */
    public function testValidate(string $received, string $range, bool $result): void
    {
        $this->assertSame($result, (new IPRange())->validate($received, $range));
    }

    /**
     * Test get message for invalid ip.
     *
     * @return void
     */
    public function testGetMessageForInvalidIp(): void
    {
        $instance = new IPRange();
        $instance->validate('2001:abcdf::0010', '2001:abcd::0010/125');

        $this->assertSame('Received value is not a valid ip address', $instance->getMessage());
    }

    /**
     * Test get message for not in range ip.
     *
     * @return void
     */
    public function testGetMessageForNotInRangeIp(): void
    {
        $instance = new IPRange();
        $instance->validate('2001:abcd::0009', '2001:abcd::0010/125');

        $this->assertSame('Received ip is not in (2001:abcd::0010/125) range', $instance->getMessage());
    }

    /**
     * Valid Suffix provider.
     *
     * @return array
     */
    public function validSuffixProvider(): array
    {
        $tmp = [];

        for ($i = 1; $i < 129; $i++) {
            $tmp[] = [$i];
        }

        return $tmp;
    }

    /**
     * Test valid CIDR with all valid suffix.
     *
     * @dataProvider validSuffixProvider
     *
     * @return void
     */
    public function testValidCidrForAllValidSuffixes(int $suffix): void
    {
        $this->assertSame(false, (new IPRange())->validate('2001:abcd::0010', "2001:abcd::0010/{$suffix}"));
    }

    /**
     * Empty suffix provider.
     *
     * @return array
     */
    public function emptySuffixProvider(): array
    {
        return [
            ['2001:abcd::0010'],
            ['2001:abcd::0010/'],
            ['2001:abcd::0010/0']
        ];
    }

    /**
     * Test invalid CIDR with empty bits for suffix.
     *
     * @dataProvider emptySuffixProvider
     *
     * @return void
     */
    public function testInvalidCidrForEmptySuffix(string $range): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Range must be in valid IP/CIDR format, empty bits for suffix.');

        $instance = new IPRange();
        $instance->validate('2001:abcd::0010', $range);
    }

    /**
     * Invalid suffix range provider.
     *
     * @return array
     */
    public function invalidSuffixRangeProvider(): array
    {
        return[
            [-3],[-2],[-1],[129],[130],[131]
        ];
    }

    /**
     * Test invalid CIDR with invalid bits for suffix.
     *
     * @dataProvider invalidSuffixRangeProvider
     *
     * @return void
     */
    public function testInvalidCidrForInvalidSuffixRange(int $suffix): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Range must be in valid IP/CIDR format, invalid bits suffix range.');

        $instance = new IPRange();
        $instance->validate('2001:abcd::0010', "2001:abcd::0010/{$suffix}");
    }

    /**
     * Test invalid CIDR with invalid address.
     *
     * @return void
     */
    public function testInvalidCidrForInvalidAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Range must be in valid IP/CIDR format, invalid address.');

        $instance = new IPRange();
        $instance->validate('2001:abcd::0010', '2001:abcdf::0010/29');
    }
}
