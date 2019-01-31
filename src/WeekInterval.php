<?php

namespace DavisPeixoto\ReportDates;

use DateTime;

class WeekInterval
{
    /**
     * @var DateTime $start_date
     */
    private $start_date;

    /**
     * @var DateTime $end_date
     */
    private $end_date;

    /**
     * @var integer $interval
     */
    private $interval;

    public function __construct(DateTime $start_date, DateTime $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;

        $interval = $end_date->diff($start_date);
        $this->interval = (int)$interval->format('%a') + 1;
    }

    /**
     * @return DateTime
     */
    public function getStartDate(): DateTime
    {
        return $this->start_date;
    }

    /**
     * @return DateTime
     */
    public function getEndDate(): DateTime
    {
        return $this->end_date;
    }

    /**
     * @return int
     */
    public function getWorkingDays(): int
    {
        return $this->interval;
    }

    /**
     * @return string
     */
    public function getWeekNumber(): string
    {
        return $this->end_date->format('W');
    }

    /**
     * @return string
     */
    public function getYearWeek(): string
    {
        return $this->end_date->format('oW');
    }

    /**
     * @return string
     */
    public function getYearMonth(): string
    {
        return $this->end_date->format('Ym');
    }
}
