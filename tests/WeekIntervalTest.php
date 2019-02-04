<?php

namespace DavisPeixoto\ReportDates\Tests;

use DateTime;
use DateTimeZone;
use DavisPeixoto\ReportDates\DatesConfig;
use DavisPeixoto\ReportDates\WeekInterval;
use DavisPeixoto\ReportDates\DayNumber;
use Exception;
use PHPUnit\Framework\TestCase;

class WeekIntervalTest extends TestCase
{
    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param DatesConfig $config
     * @param integer $expectedBusinessDays
     * @param integer $expectedTotalDays
     *
     * @dataProvider constructorProvider
     */
    public function testConstructor($startDate, $endDate, $config, $expectedBusinessDays, $expectedTotalDays)
    {
        $weekInterval = new WeekInterval($startDate, $endDate, $config);
        $this->assertEquals($expectedBusinessDays, $weekInterval->getBusinessDays());
        $this->assertEquals($expectedTotalDays, $weekInterval->getTotalDays());
    }

    /**
     * @return array
     * @throws Exception
     */
    public function constructorProvider()
    {
        $start01 = new DateTime('2019-01-06');
        $start02 = new DateTime('2019-01-07');
        $start03 = new DateTime('2019-01-07');
        $start04 = new DateTime('2019-01-01');
        $start05 = new DateTime('2019-01-27');

        $end01 = new DateTime('2019-01-12');
        $end02 = new DateTime('2019-01-11');
        $end03 = new DateTime('2019-01-13');
        $end04 = new DateTime('2019-01-05');
        $end05 = new DateTime('2019-01-31');

        $expectedDays01 = 5;
        $expectedDays02 = 7;
        $expectedDays03 = 6;
        $expectedDays04 = 3;
        $expectedDays05 = 4;

        $configDays01 = [
            new DayNumber(DayNumber::MONDAY),
            new DayNumber(DayNumber::TUESDAY),
            new DayNumber(DayNumber::WEDNESDAY),
            new DayNumber(DayNumber::THURSDAY),
            new DayNumber(DayNumber::FRIDAY)
        ];

        $configDays02 = [
            new DayNumber(DayNumber::TUESDAY),
            new DayNumber(DayNumber::WEDNESDAY),
            new DayNumber(DayNumber::THURSDAY)
        ];

        $configDays03 = [
            new DayNumber(DayNumber::MONDAY),
            new DayNumber(DayNumber::TUESDAY),
            new DayNumber(DayNumber::WEDNESDAY),
            new DayNumber(DayNumber::THURSDAY),
            new DayNumber(DayNumber::FRIDAY),
            new DayNumber(DayNumber::SATURDAY)
        ];

        $tz01 = new DateTimeZone('UTC');
        $tz02 = new DateTimeZone('America/Sao_Paulo');

        $config01 = new DatesConfig($tz01, new DayNumber(DayNumber::SUNDAY), $configDays01);
        $config02 = new DatesConfig($tz01, new DayNumber(DayNumber::SUNDAY), $configDays02);
        $config03 = new DatesConfig($tz01, new DayNumber(DayNumber::SUNDAY), $configDays03);
        $config04 = new DatesConfig($tz02, new DayNumber(DayNumber::SUNDAY), $configDays01);

        return [
            [$start01, $end01, $config01, $expectedDays01, $expectedDays02],
            [$start02, $end02, $config02, $expectedDays04, $expectedDays01],
            [$start03, $end03, $config03, $expectedDays03, $expectedDays02],
            [$start01, $end01, $config04, $expectedDays01, $expectedDays02],
            [$start04, $end04, $config01, $expectedDays05, $expectedDays01],
            [$start05, $end05, $config01, $expectedDays05, $expectedDays01]
        ];
    }
}
