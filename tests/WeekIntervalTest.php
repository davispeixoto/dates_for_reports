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
     *
     * @dataProvider constructorProvider
     */
    public function testConstructor(
        $startDate,
        $endDate,
        $config,
        $expectedBusinessDays
    )
    {
        $weekInterval = new WeekInterval($startDate, $endDate, $config);
        $this->assertEquals($expectedBusinessDays, $weekInterval->getBusinessDays());
    }

    /**
     * @return array
     * @throws Exception
     */
    public function constructorProvider()
    {
        $start01 = new DateTime('2019-01-01');
        $start02 = new DateTime('2019-01-01');
        $start03 = new DateTime('2019-01-01');
        $start04 = new DateTime('2019-01-01');

        $end01 = new DateTime('2019-01-01');
        $end02 = new DateTime('2019-01-01');
        $end03 = new DateTime('2019-01-01');
        $end04 = new DateTime('2019-01-01');

        $expectedDays01 = 0;
        $expectedDays02 = 0;
        $expectedDays03 = 0;
        $expectedDays04 = 0;

        $configDays01 = [
            new DayNumber(DayNumber::SUNDAY),
            new DayNumber(DayNumber::SUNDAY),
            new DayNumber(DayNumber::SUNDAY),
            new DayNumber(DayNumber::SUNDAY)
        ];

        $tz01 = new DateTimeZone('UTC');
        $tz02 = new DateTimeZone('America/Sao_Paulo');

        $config01 = new DatesConfig($tz01, new DayNumber(DayNumber::SUNDAY), $configDays01);
        $config02 = new DatesConfig($tz01, new DayNumber(DayNumber::SUNDAY), $configDays01);
        $config03 = new DatesConfig($tz01, new DayNumber(DayNumber::SUNDAY), $configDays01);
        $config04 = new DatesConfig($tz02, new DayNumber(DayNumber::SUNDAY), $configDays01);

        return [
            [$start01, $end01, $config01, $expectedDays01],
            [$start02, $end02, $config02, $expectedDays02],
            [$start03, $end03, $config03, $expectedDays03],
            [$start04, $end04, $config04, $expectedDays04]
        ];
    }
}
