<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Rules\Min;
use PHPUnit\Framework\TestCase;

/**
 * Min Test
 */
class MinTest extends TestCase
{
    /**
     * Number provider.
     *
     * @return array
     */
    public function numberProvider(): array
    {
        return [
          [10, true],
          [11, true],
          [12, false],
          [13, false],
          [14, false],
          [15, false]
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider numberProvider
     *
     * @param int $number
     * @param bool $result
     */
    public function testValidate(int $number, bool $result): void
    {
        $this->assertEquals($result, (new Min())->validate($number, 12));
    }
}
