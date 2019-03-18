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

use Linna\Filter\Rules\Ip;
use PHPUnit\Framework\TestCase;

/**
 * Ip Test
 */
class IpTest extends TestCase
{
    /**
     * Ip provider.
     *
     * @return array
     */
    public function ipProvider(): array
    {
        return [
            ['127.0.0', true],
            ['2001:db8:0:1', true],
            ['1200::AB00:1234::2552:7777:1313', true],
            ['127.0.0.1', false],
            ['1200:0000:AB00:1234:0000:2552:7777:1313', false],
            ['1200:0000:AB00:1234:O000:2552:7777:1313', true],
            ['2000::', false],
            ['2002:c0a8:101::42', false],
            ['2003:dead:beef:4dad:23:46:bb:101', false],
            ['::192:168:0:1', false],
            ['2001:3452:4952:2837::', false],
            ['::', false],
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider ipProvider
     *
     * @param string $ip
     * @param bool   $result
     *
     * @return void
     */
    public function testValidate($ip, bool $result): void
    {
        $this->assertSame($result, (new Ip())->validate($ip));
    }

    /**
     * Test get message.
     *
     * @return void
     */
    public function testGetMessage(): void
    {
        $instance = new Ip();
        $instance->validate('127.0.0');

        $this->assertSame('Received value is not a valid ip address', $instance->getMessage());
    }
}
