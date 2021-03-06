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
use Linna\Filter\Filter;
use Linna\Filter\Rules\CustomRule;
use PHPUnit\Framework\TestCase;

/**
 * Filter Test
 */
class FilterTest extends TestCase
{
    /**
     * Rules and data provider.
     *
     * @return array
     */
    public function oneRulesDataResultProvider(): array
    {
        return [
          ['numbercompare > 18','19',0],
          ['numbercompare > 18','18',1],
          ['numbercompare > 18','17',1],
          ['numbercompare < 18','19',1],
          ['numbercompare < 18','18',1],
          ['numbercompare < 18','17',0],
          ['numberinterval >< 18 20','17',1],
          ['numberinterval >< 18 20','18',1],
          ['numberinterval >< 18 20','19',0],
          ['numberinterval >< 18 20','20',1],
          ['numberinterval >< 18 20','21',1],
          ['stringlencompare > 4','pas',1],
          ['stringlencompare > 4','pass',1],
          ['stringlencompare > 4','passw',0],
          ['stringlencompare < 4','pas',0],
          ['stringlencompare < 4','pass',1],
          ['stringlencompare < 4','passw',1],
          ['email','foo@baz.com',0],
          ['email','foobaz.com',1],
          ['email','foo@bazcom',1],
          ['email','foobazcom',1],
          ['required email','',2],
          ['required email','f',1],
          ['required email','foobazcom',1],
          ['required email','foo@baz.com',0],
        ];
    }

    /**
     * Test Filter.
     *
     * @dataProvider oneRulesDataResultProvider
     *
     * @param string $rule
     * @param string $data
     * @param int    $errors
     *
     * @return void
     */
    public function testFilterOne(string $rule, string $data, int $errors): void
    {
        $filter = new Filter();
        $filter->filter($data, $rule);

        $this->assertSame($errors, $filter->getErrors());
        $this->assertSame($errors, \count($filter->getMessages()['data']));
    }

    /**
     * Rules and data provider.
     *
     * @return array
     */
    public function multiRulesDataResultProvider(): array
    {
        return [
          [['age numbercompare > 18'],['agge' => '18'],1], //testing missing field
          [['age numbercompare > 18'],['age' => '19'],0],
          [['age numbercompare > 18'],['age' => '18'],1],
          [['age numbercompare > 18'],['age' => '17'],1],
          [['age numbercompare < 18'],['age' => '19'],1],
          [['age numbercompare < 18'],['age' => '18'],1],
          [['age numbercompare < 18'],['age' => '17'],0],
          [['age numberinterval >=< 18 20'],['age' => '17'],1],
          [['age numberinterval >=< 18 20'],['age' => '18'],0],
          [['age numberinterval >=< 18 20'],['age' => '19'],0],
          [['age numberinterval >=< 18 20'],['age' => '20'],0],
          [['age numberinterval >=< 18 20'],['age' => '21'],1],
          [['password stringlencompare >= 4'],['password' => 'pas'],1],
          [['password stringlencompare >= 4'],['password' => 'pass'],0],
          [['password stringlencompare >= 4'],['password' => 'passw'],0],
          [['password stringlencompare <= 4'],['password' => 'pas'],0],
          [['password stringlencompare <= 4'],['password' => 'pass'],0],
          [['password stringlencompare <= 4'],['password' => 'passw'],1],
          [['email email'],['email' => 'foo@baz.com'],0],
          [['email email'],['email' => 'foobaz.com'],1],
          [['email email'],['email' => 'foo@bazcom'],1],
          [['email email'],['email' => 'foobazcom'],1],
          [['email required email'],['email' => ''],2],
          [['email required email'],['email' => 'f'],1],
          [['email required email'],['email' => 'foobazcom'],1],
          [['email required email'],['email' => 'foo@baz.com'],0],
        ];
    }

    /**
     * Test Filter.
     *
     * @dataProvider multiRulesDataResultProvider
     *
     * @param array $rule
     * @param array $data
     * @param int $error
     *
     * @return void
     */
    public function testFilterMulti(array $rule, array $data, int $error): void
    {
        $filter = new Filter();
        $filter->filter($data, $rule);

        $this->assertEquals($error, $filter->getErrors());
    }

    /**
     * Test filter with invalid rule format.
     *
     * @return void
     */
    public function testFilterWithInvalidRuleFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid types passed for data or rules.');

        $filter = new Filter();
        $filter->filter(0, 0);
    }

    /**
     * Test Filter.
     *
     * @dataProvider multiRulesDataResultProvider
     *
     * @param array $rule
     * @param array $data
     * @param int   $error
     *
     * @return void
     */
    public function testFilterMultiResultStyle(array $rule, array $data, int $error): void
    {
        /** @var mixed */
        $result = (new Filter())->filter($data, $rule);

        $this->assertEquals($error, $result->errors());
    }

    /**
     * Test filter with multiple rules.
     *
     * @return void
     */
    public function testFilterMultipleRules(): void
    {
        $rule = ['age numbercompare > 18 numbercompare < 22', 'born date Y-m-d'];
        $data = ['age' => '19', 'born' => '1998-01-01'];
        $result = ['age' => 19, 'born' => '1998-01-01'];
        $messages = ['age' => [], 'born' => []];

        $filter = new Filter();
        $filter->filter($data, $rule);

        $this->assertEquals(0, $filter->getErrors());
        $this->assertEquals($result, $filter->getData());
        $this->assertEquals($messages, $filter->getMessages());

        $this->assertIsInt($filter->getData()['age']);
    }

    /**
     * Test filter with multiple rules.
     *
     * @return void
     */
    public function testFilterMultipleRulesResultStyle(): void
    {
        $rule = ['age numbercompare > 18 numbercompare < 22', 'born date Y-m-d'];
        $data = ['age' => '19', 'born' => '1998-01-01'];
        $result = ['age' => 19, 'born' => '1998-01-01'];
        $messages = ['age' => [], 'born' => []];

        /** @var mixed */
        $r = (new Filter())->filter($data, $rule);

        $this->assertEquals(0, $r->errors());
        $this->assertEquals($result, $r->data());
        $this->assertEquals($messages, $r->messages());

        $this->assertIsInt($r->data()['age']);
    }

    /**
     * Test filter with multiple rules with missing field.
     *
     * @return void
     */
    public function testFilterMultipleRulesWithMissingField(): void
    {
        $rule = ['age numbercompare > 18 numbercompare < 22', 'born date Y-m-d'];
        $data = ['born' => '1998-01-01'];
        $result = ['born' => '1998-01-01'];

        $filter = new Filter();
        $filter->filter($data, $rule);

        $this->assertEquals(2, $filter->getErrors());
        $this->assertEquals($result, $filter->getData());
    }

    /**
     * Test filter with custom rule.
     *
     * @return void
     */
    public function testFilterWithCustomRule(): void
    {
        $filter = new Filter();
        $filter->addCustomRules([new CustomRule(
            ['checktest'],
            function (string $received): bool {
                if ($received === 'test') {
                    return true;
                }
                return false;
            }
        )]);

        $filter->filter('test', 'string slc = 4 checktest');

        $this->assertEquals(['data' => 'test'], $filter->getData());
        $this->assertEquals(0, $filter->getErrors());
        $this->assertEquals(['data' => []], $filter->getMessages());
    }
}
