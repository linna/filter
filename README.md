![Linna Filter](logo-filter.png)
<br/>
<br/>
<br/>
[![Build Status](https://travis-ci.org/linna/filter.svg?branch=master)](https://travis-ci.org/linna/filter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/linna/filter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/linna/filter/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/linna/filter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/linna/filter/?branch=master)
[![StyleCI](https://styleci.io/repos/111321128/shield?branch=master&style=flat)](https://styleci.io/repos/111321128)


This package provide filters for user input data.

## Requirements
This package require php 7.1

## Installation
With composer:
```
composer require linna/filter
```

## Usage
Filters can be used in two different ways.

### Filter One

Apply one or more rules to one values:
```php
use Linna\Filter\Filter;

$filter = new Filter();
$filter->filterOne(20, 'number between 15 25');

//int 0
var_dump($filter->getErrors());

//array empty
var_dump($filter->getMessages());

//filtered data
//array (size=1)
//  'data' => int 20
var_dump($filter->getData());
```

### Filter Multi

Apply one or more rules to many value:
```php
use Linna\Filter\Filter;

//simulate data from user form
$_POST = [
    'email' => 'user@email.com',
    'password' => 'p4ssw0rd200!',
    'age' => '25',
    'born' => '1980-01-01'
];

//setting rules
$rules = [
    'email: required email escape',
    'password: required minlength 9',
    'age: number between 15 25',
    'born: datemax Y-m-d 2000-01-01'
];

//create instance
$filter = new Filter();
$filter->filterMulti($_POST, $rules);

//int 0
var_dump($filter->getErrors());

//array empty
var_dump($filter->getMessages());

//filtered data
//array (size=4)
//  'email' => string 'pippo&#64;gmail&#46;com' (length=23)
//  'password' => string 'p4ssw0rd200!' (length=12)
//  'age' => int 25
//  'born' => 
//    object(DateTime)[43]
//      public 'date' => string '1980-01-01 00:00:00.000000' (length=26)
//      public 'timezone_type' => int 3
//      public 'timezone' => string 'Europe/Berlin' (length=13)
var_dump($filter->getData());
```