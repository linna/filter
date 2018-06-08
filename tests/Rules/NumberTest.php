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
    public function numberProvider(): array
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

    /**
     * Number type provider.
     *
     * @return array
     */
    public function numberTypeProvider(): array
    {
        return [
            ['0', 'integer'],
            ['0.0', 'integer'],
            ['0.5', 'double'],
            ['1', 'integer'],
            ['1.1', 'double'],
            ['1.2', 'double'],
            ['2', 'integer']
        ];
    }

    /**
     * Test sanitize.
     *
     * @dataProvider numberTypeProvider
     *
     * @param string $number
     * @param string $result
     */
    public function testSanitizeType(string $number, string $result): void
    {
        $instance = new Number();
        $instance->sanitize($number);
        
        $this->assertEquals($result, gettype($number));
    }
}
