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

use Linna\Filter\Rules\Required;
use PHPUnit\Framework\TestCase;

/**
 * Required Test
 */
class RequiredTest extends TestCase
{
    /**
     * Value provider.
     *
     * @return array
     */
    public function dateProvider(): array
    {
        return [
            [null, true],
            ['', true],
            ['0', false],
            [0, false]
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider dateProvider
     *
     * @param mixed $value
     * @param bool  $result
     *
     * @return void
     */
    public function testValidate($value, bool $result): void
    {
        $this->assertSame($result, (new Required())->validate($value));
    }

    /**
     * Test get message for null value.
     */
    public function testGetMessageForNull(): void
    {
        $instance = new Required();
        $instance->validate(null);

        $this->assertSame('Received value is null', $instance->getMessage());
    }

    /**
     * Test get message for zero length string.
     */
    public function testGetMessageForZeroLength(): void
    {
        $instance = new Required();
        $instance->validate('');

        $this->assertSame('Received value is a void string', $instance->getMessage());
    }
}
