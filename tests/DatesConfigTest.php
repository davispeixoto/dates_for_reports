<?php

namespace DavisPeixoto\ReportDates\Tests;

use DateTimeZone;
use DavisPeixoto\ReportDates\DatesConfig;
use DavisPeixoto\ReportDates\DayNumber;
use PHPUnit\Framework\TestCase;

class DatesConfigTest extends TestCase
{
    /**
     * @param DateTimeZone $timezone
     * @param DateTimeZone $expectedTimezone
     * @param DayNumber $weekStart
     * @param DayNumber $expectedWeekStart
     * @param DayNumber $expectedWeekEnd
     * @param DayNumber[] $businessDays
     * @param DayNumber[] $expectedBusinessDays
     *
     * @dataProvider constructProvider
     */
    public function testConstruct(
        $timezone,
        $expectedTimezone,
        $weekStart,
        $expectedWeekStart,
        $expectedWeekEnd,
        $businessDays,
        $expectedBusinessDays
    ) {
        $config = new DatesConfig($timezone, $weekStart, $businessDays);

        $this->assertEquals($expectedTimezone, $config->getTimezone());
        $this->assertEquals($expectedWeekStart, $config->getWeeksStartsOn());
        $this->assertEquals($expectedWeekEnd, $config->getWeeksEndsOn());
        $this->assertEquals($expectedBusinessDays, $config->getBusinessDays());
    }

    public static function constructProvider()
    {
        $tzDefault = new DateTimeZone('UTC');
        $tzSao = new DateTimeZone('America/Sao_Paulo');

        $wsDefault = new DayNumber(DayNumber::SUNDAY);
        $wsTuesday = new DayNumber(DayNumber::TUESDAY);

        $weDefault = new DayNumber(DayNumber::SATURDAY);
        $weMonday = new DayNumber(DayNumber::MONDAY);

        $bdDefault = [
            new DayNumber(DayNumber::MONDAY),
            new DayNumber(DayNumber::TUESDAY),
            new DayNumber(DayNumber::WEDNESDAY),
            new DayNumber(DayNumber::THURSDAY),
            new DayNumber(DayNumber::FRIDAY)
        ];

        $bdInformed1 = [
            new DayNumber(DayNumber::MONDAY),
            new DayNumber(DayNumber::TUESDAY),
            new DayNumber(DayNumber::WEDNESDAY),
            new DayNumber(DayNumber::THURSDAY),
            new DayNumber(DayNumber::FRIDAY),
            new DayNumber(DayNumber::SATURDAY)
        ];

        $bdInformed2 = [
            new DayNumber(DayNumber::TUESDAY),
            new DayNumber(DayNumber::WEDNESDAY),
            new DayNumber(DayNumber::THURSDAY)
        ];

        return [
            [null, $tzDefault, null, $wsDefault, $weDefault, null, $bdDefault],
            [$tzSao, $tzSao, null, $wsDefault, $weDefault, null, $bdDefault],
            [null, $tzDefault, $wsTuesday, $wsTuesday, $weMonday, null, $bdDefault],
            [$tzSao, $tzSao, $wsTuesday, $wsTuesday, $weMonday, null, $bdDefault],
            [null, $tzDefault, null, $wsDefault, $weDefault, $bdInformed1, $bdInformed1],
            [$tzSao, $tzSao, null, $wsDefault, $weDefault, $bdInformed2, $bdInformed2],
            [null, $tzDefault, $wsTuesday, $wsTuesday, $weMonday, $bdInformed1, $bdInformed1],
            [$tzSao, $tzSao, $wsTuesday, $wsTuesday, $weMonday, $bdInformed2, $bdInformed2]

        ];
    }
}
