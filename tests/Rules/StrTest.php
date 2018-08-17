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
     * @param bool $result
     */
    public function testValidate($string, bool $result): void
    {
        $this->assertEquals($result, (new Str())->validate($string));
    }
}
