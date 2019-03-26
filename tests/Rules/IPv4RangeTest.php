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
 * IPv4Range Test
 */
class IPv4RangeTest extends TestCase
{
    /**
     * valid IPv4 range provider.
     *
     * @return array
     */
    public function validIPv4RangeProvider(): array
    {
        return [
            ['192.168.0.46', '192.168.0.48/29', true],
            ['192.168.0.47', '192.168.0.48/29', true],
            ['192.168.0.48', '192.168.0.48/29', false],
            ['192.168.0.49', '192.168.0.48/29', false],
            ['192.168.0.50', '192.168.0.48/29', false],
            ['192.168.0.51', '192.168.0.48/29', false],
            ['192.168.0.52', '192.168.0.48/29', false],
            ['192.168.0.53', '192.168.0.48/29', false],
            ['192.168.0.54', '192.168.0.48/29', false],
            ['192.168.0.55', '192.168.0.48/29', false],
            ['192.168.0.56', '192.168.0.48/29', true],
            ['192.168.0.57', '192.168.0.48/29', true],
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider validIPv4RangeProvider
     *
     * @param string $received
     * @param string $range
     * @param bool   $result
     */
    public function testValidate(string $received, string $range, bool $result): void
    {
        $this->assertSame($result, (new IPRange())->validate($received, $range));
    }

    /**
     * Test get message for invalid ip.
     */
    public function testGetMessageForInvalidIp(): void
    {
        $instance = new IPRange();
        $instance->validate('192.168.50', '192.168.0.48/29');

        $this->assertSame('Received value is not a valid ip address', $instance->getMessage());
    }

    /**
     * Test get message for not in range ip.
     */
    public function testGetMessageForNotInRangeIp(): void
    {
        $instance = new IPRange();
        $instance->validate('192.168.0.10', '192.168.0.48/29');

        $this->assertSame('Received ip is not in (192.168.0.48/29) range', $instance->getMessage());
    }

    /**
     * Valid Suffix provider.
     *
     * @return array
     */
    public function validSuffixProvider(): array
    {
        $tmp = [];

        for ($i = 1; $i < 33; $i++) {
            $tmp[] = [$i];
        }

        return $tmp;
    }

    /**
     * Test valid CIDR with all valid suffix.
     *
     * @dataProvider validSuffixProvider
     */
    public function testValidCidrForAllValidSuffixes(int $suffix): void
    {
        $this->assertFalse((new IPRange())->validate('192.168.0.48', "192.168.0.48/{$suffix}"));
    }

    /**
     * Empty suffix provider.
     *
     * @return array
     */
    public function emptySuffixProvider(): array
    {
        return [
            ['192.168.0.48'],
            ['192.168.0.48/'],
            ['192.168.0.48/0']
        ];
    }

    /**
     * Test invalid CIDR with empty bits for suffix.
     *
     * @dataProvider emptySuffixProvider
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Range must be in valid IP/CIDR format, empty bits for suffix.
     */
    public function testInvalidCidrForEmptySuffix(string $range): void
    {
        $instance = new IPRange();
        $instance->validate('192.168.0.50', $range);
    }

    /**
     * Invalid suffix range provider.
     *
     * @return array
     */
    public function invalidSuffixRangeProvider(): array
    {
        return[
            [-3],[-2],[-1],[33],[34],[35]
        ];
    }

    /**
     * Test invalid CIDR with invalid bits for suffix.
     *
     * @dataProvider invalidSuffixRangeProvider
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Range must be in valid IP/CIDR format, invalid bits suffix range.
     */
    public function testInvalidCidrForInvalidSuffixRange(int $suffix): void
    {
        $instance = new IPRange();
        $instance->validate('192.168.0.50', "192.168.0.48/{$suffix}");
    }

    /**
     * Test invalid CIDR with invalid address.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Range must be in valid IP/CIDR format, invalid address.
     */
    public function testInvalidCidrForInvalidAddress(): void
    {
        $instance = new IPRange();
        $instance->validate('192.168.0.10', '192.168.48/29');
    }
}
