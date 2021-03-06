<div align="center">
    <a href="#"><img src="logo-linna-96.png" alt="Linna Logo"></a>
</div>

<br/>

<div align="center">
    <a href="#"><img src="logo-filter.png" alt="Linna dotenv Logo"></a>
</div>

<br/>

<div align="center">

[![Build Status](https://travis-ci.org/linna/filter.svg?branch=master)](https://travis-ci.org/linna/filter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/linna/filter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/linna/filter/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/linna/filter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/linna/filter/?branch=master)
[![StyleCI](https://styleci.io/repos/111321128/shield?branch=master&style=flat)](https://styleci.io/repos/111321128)
[![PHP 7.2](https://img.shields.io/badge/PHP-7.2-8892BF.svg)](http://php.net)

</div>

# About
This package provide filters for validate and sanitize user input data.

# Requirements
This package require php 7.2

# Installation
With composer:
```
composer require linna/filter
```

# Available Filters

## Filters
| Rule Name        | Aliases                | Description                                      | Rule Arguments | Operators           | Example Data from `$_POST`   | Example Rule                             |
|------------------|------------------------|--------------------------------------------------|----------------|---------------------|------------------------------|------------------------------------------|
| date             | dat, d                 | check for a valid date                           | 1              | none                | `['born'] = '1980-06-01'`    | `'born: date Y-m-d'`                     |
| datecompare      | datcmp, dc             | compare one date with another                    | 3              | >, <, >=, <=, =     | `['born'] = '1980-06-01'`    | `'born: datecompare < Y-m-d 1990-01-01'` |
| email            | mail, e@               | check for a valid email                          | 0              | none                | `['email'] = 'foo@mail.com'` | `'email: email'`                         |
| escape           | escp, es               | convert special chars in html entities           | 0              | none                | `['name'] = 'foo<script>'`   | `'name: escape'`                         |
| ip               | ip                     | check for a valid ip (ipv4 and ipv4)             | 0              | none                | `['host'] = 192.168.0.1`     | `'host: ip'`                             |
| iprange          | iprng, ipr             | check if provided ipv4 or ipv6 is in CIDR range  | 1              | none                | `['host'] = 192.168.0.1`     | `'host: iprange 192.168.0.1/24'`         |
| number           | num, n                 | check for a valid number and cast to number      | 0              | none                | `['age'] = 25`               | `'age: number'`                          |
| numbercompare    | numcmp, nc             | compare one number with another                  | 2              | >, <, >=, <=, =     | `['age'] = 25`               | `'age: numbercompare > 18'`              |
| numberinterval   | numint, ni             | check if a number is included or not on interval | 3              | <>, ><, <=>, >=<    | `['age'] = 25`               | `'age: numberinterval >< 18 80'`         |
| required         | req, rq                | check for null values                            | 0              | none                | `['name'] = 'foo'`           | `'name: required'`                       |
| str              | string, s              | cast to string                                   | 0              | none                | `['name'] = 'foo'`           | `'name: str'`                            |
| stringlencompare | strlencmp, strlen, slc | check the length of a string                     | 2              | >, <, >=, <=, =, != | `['name'] = 'foo'`           | `'name: stringlencompare > 2'`           |

A rule could be called with the name or with the alias. An alias help to write rules more quickly.

```php
//using rule name
$rule = 'age: number, numbercompare < 30';

//using alias
$rule = 'age: n, nc < 30';
```

## Operators
| Rule Name        | Operator | Description                   | Notes                             |
|------------------|----------|-------------------------------|-----------------------------------|
| DateCompare      | <        | less than                     |                                   |
|                  | >        | greater than                  |                                   |
|                  | <=       | less than or equal            |                                   |
|                  | >=       | greater than or equal         |                                   |
|                  | =        | equal                         | PHP === equal                     |
| NumberCompare    | <        | less than                     |                                   |
|                  | >        | greater than                  |                                   |
|                  | <=       | less than or equal            |                                   |
|                  | >=       | greater than or equal         |                                   |
| NumberInterval   | <>       | out interval, exclusive       | 8-10: 7, 11 true - 8, 9, 10 false |
|                  | ><       | in interval, exclusive        | 8-10: 9 true - 7, 8, 10, 11 false |
|                  | <=>      | out interval, inclusive       | 8-10: 7, 8, 10, 11 true - 9 false |
|                  | >=<      | in interval, inclusive        | 8-10: 8, 9, 10 true - 7, 11 false |
| StringLenCompare | <        | length less than              | PHP strlen(string) < number       |
|                  | >        | length greater than           | PHP strlen(string) > number       |
|                  | <=       | length less than or equal     | PHP strlen(string) <= number      |
|                  | >=       | length greather than or equal | PHP strlen(string) >= number      |
|                  | =        | length equal                  | PHP strlen(string) === number     |
|                  | !=       | length not equal              | PHP strlen(string) !== number     |

# Type of filters
Filters can be of two types: Validation filters or Sanitization filters. Validation filters check only if the
data respect a certain criterion, instead sanitization filters alter passed data to make them conform to a given rule.

In this package are sanitization filters only **Number** and **Escape**

> **Note:** For security reasons a Validation filter should be preferred, don't try to sanitize a bad user input, discard it!

# Usage
Filters can be used in two different ways.

## Filter one field
Apply one or more rules to one value:

```php
use Linna\Filter\Filter;

$f = new Filter();
$f->filter(20, 'number numberinterval >< 15 25');

//int 0
var_dump($f->getErrors());

//array (size=1)
//  'data' => 
//    array (size=0)
//      empty
var_dump($f->getMessages());

//array (size=1)
//  'data' => int 20
var_dump($f->getData());
```

## Filter multiple fields
Apply one or more rules to many values, it is useful for validating forms:

```php
use Linna\Filter\Filter;

//override $_POST superglobal for simulate data from user form
$_POST = [
    'email' => 'user@email.com',
    'password' => 'p4ssw0rd200!',
    'age' => '25',
    'born' => '1980-06-01',
];

//create instance
$fm = new Filter();
$fm->filter($_POST, [
    'email: required, email',
    'password: required, stringlencompare >= 12',
    'age: number, numbercompare < 30',
    'born: date Y-m-d, datecompare <= Y-m-d 1990-12-31',
]);

//int 0
var_dump($fm->getErrors());

//array (size=4)
//  'email' => 
//    array (size=0)
//      empty
//  'password' => 
//    array (size=0)
//      empty
//  'age' => 
//    array (size=0)
//      empty
//  'born' => 
//    array (size=0)
//      empty
var_dump($fm->getMessages());

//array (size=4)
//  'email' => string 'pippo@gmail.com' (length=15)
//  'password' => string 'p4ssw0rd200!' (length=12)
//  'age' => int 25
//  'born' => string '1980-06-01' (length=10)
var_dump($fm->getData());
```

# Retriving results
There are two ways for get results from filter.

Using methods from `Filter` instance.
```php
use Linna\Filter\Filter;

$f = new Filter();
$f->filter(20, 'number numberinterval >< 15 25');

$errors = $filter->getErrors();
$messages = $filter->getMessages();
$data = $filter->getData();
```

Using result object:
```php
use Linna\Filter\Filter;

$f = new Filter();
$result = $f->filter(20, 'number numberinterval >< 15 25');

//or with a single expression
$result = (new Filter())->filter(20, 'number numberinterval >< 15 25');

$errors = $result->errors();
$messages = $result->messages();
$data = $result->data();
```

# Rule syntax
Parser can accept rules formatted in varius way.  

First word must be the name of the input, same present as index in input array.
```php
//override $_POST superglobal for simulate data from user form
$_POST = [
    'email' => 'pippo@gmail.com',
    'password' => 'p4ssw0rd200!',
    'age' => '25',
    'born' => '1980-06-01',
];

$rules = [
    'email required email',
    'password required stringlencompare >= 12',
    'age number numberinterval >=< 20 30',
    'born date Y-m-d datecompare <= Y-m-d 1990-12-31',
];
```

Can be used for separate the words and params of rules this chars: `:` `;` `,`

Input name separator `:`
```php
$rules = [
    'email: required email',
    'password: required stringlencompare >= 12',
    'age: number numberinterval >=< 20 30',
    'born: date Y-m-d datecompare <= Y-m-d 1990-12-31',
];
```

Input name separator `:` 
Rules separator `,`
```php
$rules = [
    'email: required, email',
    'password: required, stringlencompare >= 12',
    'age: number, numberinterval >=< 20 30',
    'born: date Y-m-d, datecompare <= Y-m-d 1990-12-31',
];
```

Input name separator `:`
Rules separator `;`
Rule arguments separator `,`
```php
$rules = [
    'email: required; email',
    'password: required; stringlencompare >=, 12',
    'age: number; numberinterval >=<, 20, 30',
    'born: date Y-m-d; datecompare <=, Y-m-d, 1990-12-31',
];
```

Must be used for params that contain spaces one of this chars: `"` `'`
```php
$rules = [
    'email: required email',
    'password: required stringlencompare >= 12',
    'age: number numberinterval >=< 20 30',
    'born: date "Y m d" datecompare <= "Y m d" "1990 12 31"',
];

$rules = [
    "email: required email",
    "password: required stringlencompare >= 12",
    "age: number numberinterval >=< 20 30",
    "born: date \"Y m d\" datecompare <= \"Y m d\" \"1990 12 31\"",
];

$rules = [
    "email: required email",
    "password: required stringlencompare >= 12",
    "age: number numberinterval >=< 20 30",
    "born: date 'Y m d' datecompare <= 'Y m d' '1990 12 31'",
];

$rules = [
    'email: required email',
    'password: required stringlencompare >= 12',
    'age: number numberinterval >=< 20 30',
    'born: date \'Y m d\' datecompare <= \'Y m d\' \'1990 12 31\'',
];
```

# Custom Rules
Custom rules give the possibility of expand the filter predefined ruleset.

## Validate
```php
$customRules = [];
$customRules[] = new CustomRule(
    //alias
    ['hellocheck'],
    //callback
    //check if word hello is inside of a phrase
    function (string $received): bool {
        if (strpos(strtolower($received), 'hello') === false) {
            return false;
        }

        return true;
    }
);

$filter = new Filter();
$filter->addCustomRules($customRules);

//test passed
$r = $filter->filter('Hello World', 'hellocheck');

//array (size=1)
//  'data' => string 'Hello World' (length=11)
var_dump($r->data());

//int 0
var_dump($r->errors());

//array (size=1)
//  'data' => 
//    array (size=0)
//      empty
var_dump($r->messages());


//test fails
$r = $filter->filter('Heo World', 'hellocheck');

//array (size=1)
//  'data' => string 'Heo World' (length=9)
var_dump($r->data());

//int 1
var_dump($r->errors());

//array (size=1)
//  'data' => 
//    array (size=1)
//      0 => string 'Value provided not pass CustomRule (hellocheck) test' (length=52)
var_dump($r->messages());
```

## Sanitize
```php
$customRules = [];
$customRules[] = new CustomRule(
    //alias
    ['emailtoletters'],
    //callback
    //replace dot and at chars with literal name
    function (string &$received): void {
        $received = str_replace('@', ' at ', $received);
        $received = str_replace('.', ' dot ', $received);
    }
);

$filter = new Filter();
$filter->addCustomRules($customRules);

$r = $filter->filter('sebastian.rapetti@alice.it', 'emailtoletters');

//array (size=1)
//  'data' => string 'sebastian dot rapetti at alice dot it' (length=37)
var_dump($r->data());

//int 0
var_dump($r->errors());

//array (size=1)
//  'data' => 
//    array (size=0)
//      empty
var_dump($r->messages());
```

Custom Rule should have:
- At least one alias.

And for callback function:
- At least one argument, rapresenting the received value.
- Return type, bool or void.

> **Note:** For implementing a sanitize custom rule, closure must have only one argument and this argument must be passed for reference.
