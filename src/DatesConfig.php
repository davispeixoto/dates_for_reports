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
     * @var DayNumber[]
     */
    private $businessDays;

    /**
     * DatesConfig constructor.
     *
     * @param DateTimeZone|null $timezone
     * @param DayNumber|null $weeksStartsOn
     * @param DayNumber[]|null $businessDays
     */
    public function __construct(
        DateTimeZone $timezone = null,
        DayNumber $weeksStartsOn = null,
        array $businessDays = null
    ) {
        $this->timezone = $timezone ?? new DateTimeZone('UTC');
        $this->weeksStartsOn = $weeksStartsOn ?? new DayNumber(DayNumber::SUNDAY);
        $this->businessDays = $businessDays ?? [
            new DayNumber(DayNumber::MONDAY),
            new DayNumber(DayNumber::TUESDAY),
            new DayNumber(DayNumber::WEDNESDAY),
            new DayNumber(DayNumber::THURSDAY),
            new DayNumber(DayNumber::FRIDAY)
        ];
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

    /**
     * @return DayNumber[]
     */
    public function getBusinessDays(): array
    {
        return $this->businessDays;
    }

    /**
     * @return DayNumber
     */
    public function getWeeksEndsOn(): DayNumber
    {
        $output = new DayNumber(DayNumber::SATURDAY);

        if (!$this->weeksStartsOn->equals(new DayNumber(DayNumber::SUNDAY))) {
            $aux = (int)$this->weeksStartsOn->getValue() - 1;

            foreach (DayNumber::values() as $day) {
                if ((int)$day->getValue() === $aux) {
                    $output = clone $day;
                    break;
                }
            }
        }

        return $output;
    }
}
