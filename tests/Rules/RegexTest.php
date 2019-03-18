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

use InvalidArgumentException;
use Linna\Filter\Rules\Regex;
use PHPUnit\Framework\TestCase;

/**
 * Regex Test
 */
class RegexTest extends TestCase
{
    /**
     * Regex provider.
     *
     * @return array
     */
    public function regexProvider(): array
    {
        return [
            ['Linna', '/^Linna$/', false],
            ['linna', '/^Linna$/', true]
        ];
    }

    /**
     * Test validate.
     *
     * @dataProvider regexProvider
     *
     * @param string $value
     * @param string $regex
     * @param bool   $result
     *
     * @return void
     */
    public function testValidate(string $value, string $regex, bool $result): void
    {
        $this->assertSame($result, (new Regex())->validate($value, $regex));
    }

    /**
     * Test invalid regex.
     *
     * @return void
     */
    public function testInvalidRegex(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid regex provided /^$/g.');

        $this->assertSame(true, (new Regex())->validate('hello', '/^$/g'));
    }

    /**
     * Test get message.
     *
     * @return void
     */
    public function testGetMessage(): void
    {
        $instance = new Regex();
        $instance->validate('hello', '/^Hello$/');

        $this->assertSame("Received value not match regex /^Hello$/", $instance->getMessage());
    }
}
