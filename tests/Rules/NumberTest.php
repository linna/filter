<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\Number;
use PHPUnit\Framework\TestCase;

/**
 * Number Test
 */
class NumberTest extends TestCase
{
    /**
     * Number provider.
     *
     * @return array
     */
    public function numberProvider() : array
    {
        return [
            [1, false],
            [1.1, false],
            ['1', false],
            ['1.1', false],
            ['1a', true],
            [true, true],
            [[], true],
            [(object)[], true],
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider numberProvider
     *
     * @param mixed $number
     * @param bool $result
     */
    public function testValidate($number, bool $result): void
    {
        $this->assertEquals($result, (new Number())->validate($number));
    }

    /**
     * Test sanitize.
     *
     * @dataProvider numberProvider
     *
     * @param mixed $number
     * @param bool $result
     */
    public function testSanitize($number, bool $result): void
    {
        $instance = new Number();
        $validated = $instance->validate($number);

        if (!$validated) {
            $temp = $number;
            $instance->sanitize($temp);
            
            if ((fmod((float) $number, 1.0) === 0.0)) {
                $this->assertSame((int)$number, $temp);
            }
            
            if ((fmod((float) $number, 1.0) !== 0.0)) {
                $this->assertSame((float)$number, $temp);
            }
        }

        $this->assertEquals($result, $validated);
    }
}
