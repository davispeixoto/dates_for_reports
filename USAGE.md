# Use cases and common usages

## Get the weeks inside a month
Often you need to display a month, broken by weeks. The problem is that
rarely a month starts or ends in a perfect match with a week start or end

Take for example, 2019 January. It starts on a Tuesday, and ends in a Thursday

```php
<?php
use DavisPeixoto\ReportDates\Dates;

$from = new DateTime('2019-01-01');
$to = new DateTime('2019-01-31');

$date_helper = new Dates();
$weeks = $date_helper->getWeeksBreak($from, $to, true);

foreach ($weeks as $week) {
    $businessDaysAmount = $week->getBusinessDays();
    // do something with dates here
}
```

## Get a number of weeks, regardless the month
Another common case is to get 6/7/8 weeks, starting last week or in the
current week.

Follow an example on how to get that.

```php
<?php
use DavisPeixoto\ReportDates\Dates;

$from = new DateTime();
$to = clone $from;
$interval = new DateInterval('P7W'); // 7 weeks period
$to->add($interval);

$date_helper = new Dates();
$weeks = $date_helper->getWeeksBreak($from, $to, true, true);

foreach ($weeks as $week) {
    $businessDaysAmount = $week->getBusinessDays();
    // do something with dates here
}
```

## Reassemble a week, based on year week and year month
To reassemble a given week you may need it by a specific month. This is why
we accept two parameters here. One for the year week (which uses the 
[ISO spec](https://en.wikipedia.org/wiki/ISO_week_date))
and the year month, so the library can return the appropriate week slice

## Timezones other than UTC
By default this library considers UTC as timezone. However you can
define a different one. It also fixes the daylight savings time one hour
shifts.

## Different weeks counting
You can also start a week into a day different that Sunday, according to your 
needs