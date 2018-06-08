<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\MinLength;
use PHPUnit\Framework\TestCase;

/**
 * MinLength Test
 */
class MinLengthTest extends TestCase
{
    /**
     * String provider.
     *
     * @return array
     */
    public function stringProvider(): array
    {
        return [
          ['', true],
          ['s', true],
          ['st', true],
          ['str', false],
          ['stri', false],
          ['strin', false],
          ['string', false]
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider stringProvider
     *
     * @param string $string
     * @param bool $result
     */
    public function testValidate(string $string, bool $result): void
    {
        $this->assertEquals($result, (new MinLength())->validate($string, 3));
    }
}
