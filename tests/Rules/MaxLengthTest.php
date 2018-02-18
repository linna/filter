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
     * Test max length.
     *
     * @dataProvider stringProvider
     *
     * @param string $value
     * @param bool $result
     */
    public function testMaxLength(string $value, bool $result): void
    {
        $this->assertEquals($result, (new MaxLength())->validate($value, 3));
    }
}
