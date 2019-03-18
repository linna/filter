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

use Linna\Filter\Rules\Str;
use PHPUnit\Framework\TestCase;

/**
 * Str Test
 */
class StrTest extends TestCase
{
    /**
     * String provider.
     *
     * @return array
     */
    public function stringProvider(): array
    {
        return [
            [1, true],
            [1.1, true],
            ['1', false],
            ['1.1', false],
            ['1a', false],
            [true, true],
            [[], true],
            [(object)[], true],
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider stringProvider
     *
     * @param mixed $string
     * @param bool  $result
     *
     * @return void
     */
    public function testValidate($string, bool $result): void
    {
        $this->assertEquals($result, (new Str())->validate($string));
    }

    /**
     * Test get message.
     *
     * @return void
     */
    public function testGetMessage(): void
    {
        $notString = 1;

        $instance = new Str();
        $instance->validate($notString);

        $this->assertSame('Received value is not a string', $instance->getMessage());
    }
}
