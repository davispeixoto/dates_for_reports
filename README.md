# Dates for reports

This is a library with a few dates helpers for using at reports. 
Mainly getting an array of dates to use for querying and/or displaying

[![Latest Stable Version](https://img.shields.io/packagist/v/davispeixoto/dates_for_reports.svg)](https://packagist.org/packages/davispeixoto/dates_for_reports)
[![Total Downloads](https://img.shields.io/packagist/dt/davispeixoto/dates_for_reports.svg)](https://packagist.org/packages/davispeixoto/dates_for_reports)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/davispeixoto/dates_for_reports/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/davispeixoto/dates_for_reports/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/davispeixoto/dates_for_reports/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/davispeixoto/dates_for_reports/?branch=master)
[![Build Status](https://travis-ci.org/davispeixoto/dates_for_reports.svg?branch=master)](https://travis-ci.org/davispeixoto/dates_for_reports)

## Installation
This package can be installed via [Composer](http://getcomposer.org) by requiring the
`davispeixoto/dates_for_reports` package in your project's `composer.json`.

```json
{
    "require": {
        "davispeixoto/dates_for_reports": "~1.0"
    }
}
```

Or

```sh
$ php composer.phar require davispeixoto/dates_for_reports
```

And running a composer update from your terminal:
```sh
php composer.phar update
```

## Usage
To use it, first you need to create the status you are going to use 
for representing your states.

```php
<?php
use DavisPeixoto\ReportDates\Dates;

$date_helper = new Dates();
$weeks = $date_helper->getWeeksBreak($from, $to);

foreach ($weeks as $week) {
    $label = $week->getLabel();
    $businessDaysAmount = $week->getBusinessDays();
    // do something with dates here
}
```



## License
This software is licensed under the [MIT license](http://opensource.org/licenses/MIT)

## Versioning
This project follows the [Semantic Versioning](http://semver.org/)

## Contributing and Quality standards and tests
This package strive to have the best as of now to ensure its quality. We count on
unit tests, behavior tests and static analysis tools.

### Static tools
Several static analysis tools are used to ensure a good design and architecture.
1. PHP Mess Detector
2. PHPStan
3. PHP Code Sniffer (we use PSR-2 style)
4. PHP Code Sniffer Fixer

The first two gives some metric of cyclomatic complexity and naming conventions.
The last two are the basic for ensure we have the proper code style (PSR-2).

We also can count on PHPStorm/IDE related inspections.

### BDD
The directory features has all .feature files, written in Gherkin. The subdirectory
bootstrap contains the BDD-implementation of Behat classes.

We do not create CRUD features, but all business rules should be written here,
through examples.

### Unit Tests
The tests directory has the unit tests, written in phpunit 6+, and strive to follow
the rules:
 
1. They are designed to test all entities and services
2. Simple getters and setters can be skipped 
3. Simple constructors and destructors can be skipped
4. All business rules should be covered
5. All public methods that cannot be skipped as per above rule should be tested
6. All private methods should be covered through proper inputs into public methods
7. It is highly recommended to use data providers for passing parameters to 
test methods

## Thanks
For all PHP community