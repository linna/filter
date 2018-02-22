<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\MaxLength;
use PHPUnit\Framework\TestCase;

/**
 * MaxLength Test
 */
class MaxLengthTest extends TestCase
{
    
    /**
     * String provider.
     *
     * @return array
     */
    public function stringProvider() : array
    {
        return [
          ['', false],
          ['s', false],
          ['st', false],
          ['str', false],
          ['stri', true],
          ['strin', true],
          ['string', true]
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
        $this->assertEquals($result, (new MaxLength())->validate($string, 3));
    }
}
