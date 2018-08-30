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

use Linna\Filter\Rules\Escape;
use PHPUnit\Framework\TestCase;

/**
 * Escape Test
 */
class EscapeTest extends TestCase
{
    /**
     * String provider.
     *
     * @return array
     */
    public function stringProvider(): array
    {
        return [
            [' !"#$%&'."'()*+,-./0", ' &#33;&#34;&#35;&#36;&#37;&#38;&#39;&#40;&#41;&#42;&#43;&#44;&#45;&#46;&#47;0'],
            ['9:;<=>?@A', '9&#58;&#59;&#60;&#61;&#62;&#63;&#64;A'],
            ['Z[\]^_`a', 'Z&#91;&#92;&#93;&#94;&#95;&#96;a'],
            ['z{|}~', 'z&#123;&#124;&#125;&#126;'],
            ['0123456789','0123456789'],
            ['abcdefghijklmnopqrstuvwxyz','abcdefghijklmnopqrstuvwxyz'],
            ['ABCDEFGHIJKLMNOPQRSTUVWXYZ','ABCDEFGHIJKLMNOPQRSTUVWXYZ'],
            ['0123456789 abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ','0123456789 abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ'],
        ];
    }

    /**
     * Test sanitize.
     *
     * @dataProvider stringProvider
     *
     * @param string $string
     * @param string $result
     */
    public function testSanitize(string $string, string $result): void
    {
        (new Escape())->sanitize($string);

        $this->assertSame($result, $string);
    }

    /**
     * Test get message.
     */
    public function testGetMessage(): void
    {
        $string = '12345678()';

        $instance = new Escape();
        $instance->sanitize($string);

        $this->assertSame('', $instance->getMessage());
    }
}
