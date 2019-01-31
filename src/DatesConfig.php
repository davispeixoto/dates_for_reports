<?php

namespace DavisPeixoto\ReportDates;

use DateTimeZone;

class DatesConfig
{
    /**
     * @var DateTimeZone
     */
    private $timezone;

    /**
     * @var DayNumber
     */
    private $weeksStartsOn;

    /**
     * DatesConfig constructor.
     *
     * @param DateTimeZone|null $timezone
     * @param DayNumber|null $weeksStartsOn
     */
    public function __construct(DateTimeZone $timezone = null, DayNumber $weeksStartsOn = null)
    {
        $this->timezone = $timezone ?? new DateTimeZone('UTC');
        $this->weeksStartsOn = $weeksStartsOn ?? new DayNumber(DayNumber::SUNDAY);
    }

    /**
     * @return DateTimeZone
     */
    public function getTimezone(): DateTimeZone
    {
        return $this->timezone;
    }

    /**
     * @return DayNumber
     */
    public function getWeeksStartsOn(): DayNumber
    {
        return $this->weeksStartsOn;
    }


}
