
# Linna Filter Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][v0.3.0](https://github.com/linna/filter/compare/v0.2.0...v0.3.0) - 2018-XX-XX 

### Added
* `Linna\Filter\Result` class, abstract, returned by `Linna\Filter\Filter->filter()` as anonymous class
* `Linna\Filter\Rules\CustomRule` for declare user validation and sanitization custom rules
* `Linna\Filter\Rules\Ip` check for valid ipv4 and ipv6 address
* `Linna\Filter\Rules\IPRange` check if provided ipv4/ipv6 is in CIDR range
* `Linna\Filter\Rules\StringLenCompare` new alias `strlen`
* Namespace for tests

### Changed
* `Linna\Filter\Filter->filterOne()` and `Linna\Filter\Filter->filterMulti()` merged in `Linna\Filter\Filter->filter()`
* `Linna\Filter\Lexer` class improved
* `Linna\Filter\Rules\Regex` now throw an `InvalidArgumentException` if invalid regex is provided

### Fixed
* [Issue #2](https://github.com/linna/filter/issues/2)

## [v0.2.0](https://github.com/linna/filter/compare/v0.1.0...v0.2.0) - 2018-07-28

### Added
* Rule aliases
* New rule and more human readable message system
* `Linna\Filter\Filter->filterOne()` and `Linna\Filter\Filter->filterMulti()` methods now return a results object
* `Linna\Filter\Rules\NumberIntervall` check if a number is inside or outside a range with operators ><, <>, >=<, <=
* `Linna\Filter\Rules\NumberCompare` check and compare numbers with operators <, >, >=, <=, =
* `Linna\Filter\Rules\Regex` check if value match a regex
* `Linna\Filter\Rules\StringLenCompare` check length of a string with operators <, >, >=, <=, =, !=
* `Linna\Filter\Rules\Str` sanitize strings (convert data type to string)

### Removed
* `Linna\Filter\Rules\Between`
* `Linna\Filter\Rules\MaxLength`
* `Linna\Filter\Rules\Max`
* `Linna\Filter\Rules\Min`
* `Linna\Filter\Rules\MinLength`

## [Initial Release][v0.1.0](https://github.com/linna/filter/compare/v0.1.0...master) - 2018-05-29

### Added
* `Linna\Filter\Rules\Between` validate numbers, between two values
* `Linna\Filter\Rules\Date` validate a date
* `Linna\Filter\Rules\DateCompare` validate dates with operators <, >, >=, <=, =
* `Linna\Filter\Rules\Email` validate an email
* `Linna\Filter\Rules\Escape` convert special chars to html entities
* `Linna\Filter\Rules\Max` validate numbers, lower than 
* `Linna\Filter\Rules\MaxLength` validate strings length, lower than
* `Linna\Filter\Rules\Min` validate numbers, higher than
* `Linna\Filter\Rules\MinLength` validate strings length, higher than
* `Linna\Filter\Rules\Number` sanitize numbers (convert data type to int or float)
