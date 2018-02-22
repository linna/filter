<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

use Linna\Filter\Filter;
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
    public function oneRulesDataResultProvider() : array
    {
        return [
          ['min 18','19',0],
          ['min 18','18',0],
          ['min 18','17',1],
          ['max 18','19',1],
          ['max 18','18',0],
          ['max 18','17',0],
          ['between 18 20','17',1],
          ['between 18 20','18',0],
          ['between 18 20','19',0],
          ['between 18 20','20',0],
          ['between 18 20','21',1],
          ['minlength 4','pas',1],
          ['minlength 4','pass',0],
          ['minlength 4','passw',0],
          ['maxlength 4','pas',0],
          ['maxlength 4','pass',0],
          ['maxlength 4','passw',1],
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
     * @param int $error
     */
    public function testFilterOne(string $rule, string $data, int $error): void
    {
        $filter = new Filter();
        $filter->filterOne($data, $rule);
        
        $this->assertEquals($error, $filter->getErrors());
    }
    
    /**
     * Rules and data provider.
     *
     * @return array
     */
    public function multiRulesDataResultProvider() : array
    {
        return [
          [['age min 18'],['age' => '19'],0],
          [['age min 18'],['age' => '18'],0],
          [['age min 18'],['agge' => '18'],1], //testing missing field
          [['age min 18'],['age' => '17'],1],
          [['age max 18'],['age' => '19'],1],
          [['age max 18'],['age' => '18'],0],
          [['age max 18'],['age' => '17'],0],
          [['age between 18 20'],['age' => '17'],1],
          [['age between 18 20'],['age' => '18'],0],
          [['age between 18 20'],['age' => '19'],0],
          [['age between 18 20'],['age' => '20'],0],
          [['age between 18 20'],['age' => '21'],1],
          [['password minlength 4'],['password' => 'pas'],1],
          [['password minlength 4'],['password' => 'pass'],0],
          [['password minlength 4'],['password' => 'passw'],0],
          [['password maxlength 4'],['password' => 'pas'],0],
          [['password maxlength 4'],['password' => 'pass'],0],
          [['password maxlength 4'],['password' => 'passw'],1],
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
     */
    public function testFilterMulti(array $rule, array $data, int $error): void
    {
        $filter = new Filter();
        $filter->filterMulti($data, $rule);
        
        $this->assertEquals($error, $filter->getErrors());
    }
    
    /**
     * Rules and data provider.
     *
     * @return array
     */
    public function rulesNumberFloatProvider() : array
    {
        return [
          [['price number min 18.5'],['price' => '19.5'],['price' => 19.5],0],
          [['price number min 18.5'],['price' => '18.5'],['price' => 18.5],0],
          [['price number min 18.5'],['price' => '17.5'],['price' => 17.5],1],
          [['price number max 18.5'],['price' => '19.5'],['price' => 19.5],1],
          [['price number max 18.5'],['price' => '18.5'],['price' => 18.5],0],
          [['price number max 18.5'],['price' => '17.5'],['price' => 17.5],0]
        ];
    }
    
    /**
     * Test filter number.
     *
     * @dataProvider rulesNumberFloatProvider
     *
     * @param array $rule
     * @param array $data
     * @param array $result
     * @param int $error
     */
    public function testFilterFloatNumber(array $rule, array $data, array $result, int $error): void
    {
        $filter = new Filter();
        $filter->filterMulti($data, $rule);
        
        $this->assertEquals($error, $filter->getErrors());
        $this->assertEquals($result, $filter->getData());
        $this->assertInternalType('float', $filter->getData()['price']);
    }
    
    /**
     * Rules and data provider.
     *
     * @return array
     */
    public function rulesNumberIntProvider() : array
    {
        return [
          [['age number min 18'],['age' => '19'],['age' => 19],0],
          [['age number min 18'],['age' => '18'],['age' => 18],0],
          [['age number min 18'],['age' => '17'],['age' => 17],1],
          [['age number max 18'],['age' => '19'],['age' => 19],1],
          [['age number max 18'],['age' => '18'],['age' => 18],0],
          [['age number max 18'],['age' => '17'],['age' => 17],0]
        ];
    }
    
    /**
     * Test filter number.
     *
     * @dataProvider rulesNumberIntProvider
     *
     * @param array $rule
     * @param array $data
     * @param array $result
     * @param int $error
     */
    public function testFilterNumber(array $rule, array $data, array $result, int $error): void
    {
        $filter = new Filter();
        $filter->filterMulti($data, $rule);
        
        $this->assertEquals($error, $filter->getErrors());
        $this->assertEquals($result, $filter->getData());
        $this->assertInternalType('integer', $filter->getData()['age']);
    }
    
    /**
     * Test filter with multiple rules.
     *
     * @param array $rule
     * @param array $data
     * @param array $result
     * @param int $error
     */
    public function testFilterMultipleRules(): void
    {
        $rule = ['age number min 18 max 22', 'born date Y-m-d'];
        $data = ['age' => '19', 'born' => '1998-01-01'];
        $result = ['age' => 19, 'born' => new DateTime('1998-01-01')];
        
        $filter = new Filter();
        $filter->filterMulti($data, $rule);
        
        $this->assertEquals(0, $filter->getErrors());
        $this->assertEquals($result, $filter->getData());
        $this->assertInternalType('integer', $filter->getData()['age']);
        
        $this->assertInstanceOf(DateTime::class, $filter->getData()['born']);
    }
    
    /**
     * Test filter with multiple rules with missing field.
     */
    public function testFilterMultipleRulesWithMissingField(): void
    {
        $rule = ['age number min 18 max 22', 'born date Y-m-d'];
        $data = ['born' => '1998-01-01'];
        $result = ['born' => new DateTime('1998-01-01')];
        
        $filter = new Filter();
        $filter->filterMulti($data, $rule);
        
        $this->assertEquals(3, $filter->getErrors());
        $this->assertEquals($result, $filter->getData());
        $this->assertInstanceOf(DateTime::class, $filter->getData()['born']);
    }
    
    /**
     * Test filter get errors messages.
     */
    public function testFilterGetMessages(): void
    {
        $rule = ['age min 18'];
        $data = [];
        
        $filter = new Filter();
        $filter->filterMulti($data, $rule);
        
        $this->assertEquals(1, $filter->getErrors());
        $this->assertEquals('Form field \'age\' missing.', $filter->getMessages()['age']['Min']);
    }
}
