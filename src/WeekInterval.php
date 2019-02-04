<?php

namespace DavisPeixoto\ReportDates;

use DateInterval;
use DateTime;
use Exception;

class WeekInterval
{
    /**
     * @var DateTime $startDate
     */
    private $startDate;

    /**
     * @var DateTime $endDate
     */
    private $endDate;

    /**
     * @var DatesConfig $config
     */
    private $config;

    /**
     * WeekInterval constructor.
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param DatesConfig $config
     */
    public function __construct(DateTime $startDate, DateTime $endDate, DatesConfig $config)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->config = $config;
    }

    /**
     * @return DateTime
     */
    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    /**
     * @return DateTime
     */
    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    /**
     * @return int
     */
    public function getBusinessDays(): int
    {
        return $this->countBusinessDays();
    }

    /**
     * @return int
     */
    public function getTotalDays(): int
    {
        return (int)$this->endDate->diff($this->startDate)->format('%a') + 1;
    }

    /**
     * @return string
     */
    public function getWeekNumber(): string
    {
        return $this->endDate->format('W');
    }

    /**
     * @return string
     */
    public function getYearWeek(): string
    {
        return $this->endDate->format('oW');
    }

    /**
     * @return string
     */
    public function getYearMonth(): string
    {
        return $this->endDate->format('Ym');
    }

    private function countBusinessDays()
    {
        $startDate = clone $this->startDate;
        $count = 0;

        while ($startDate <= $this->endDate) {
            if ($this->isBusinessDay($this->makeDayNumber($startDate))) {
                $count++;
            }

            $startDate = $this->incrementDay($startDate);
        }

        return $count;
    }

    /**
     * @param DayNumber $day
     * @return bool
     */
    private function isBusinessDay(DayNumber $day)
    {
        foreach ($this->config->getBusinessDays() as $businessDay) {
            if ($day->equals($businessDay)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param DateTime $day
     * @return bool|DayNumber
     */
    private function makeDayNumber(DateTime $day)
    {
        $values = DayNumber::values();
        foreach ($values as $dayNumber) {
            if ($dayNumber->getValue() === $day->format('w')) {
                return $dayNumber;
            }
        }

        return false;
    }

    /**
     * @param DateTime $date
     * @return DateTime
     * @throws Exception
     */
    private function incrementDay(DateTime $date)
    {
        $oneDay = new DateInterval('P1D');
        $oneHour = new DateInterval('PT1H');

        $date->add($oneDay);

        if ($date->format('h') === '01') {
            $date->sub($oneHour);
        }

        if ($date->format('h') === '23') {
            $date->add($oneHour);
        }

        return $date;
    }
}
