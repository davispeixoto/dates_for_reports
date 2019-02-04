<?php

namespace DavisPeixoto\ReportDates;

use DateInterval;
use DateTime;
use Exception;

class Dates
{
    /**
     * @var DatesConfig $config
     */
    private $config;

    /**
     * @var DateTime $startDate
     */
    private $startDate;

    /**
     * @var DateTime $endDate
     */
    private $endDate;

    /**
     * Dates constructor.
     *
     * @param DatesConfig|null $config
     */
    public function __construct(DatesConfig $config = null)
    {
        $this->config = $config ?? new DatesConfig();
    }

    /**
     * Returns an array of week intervals
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param bool $full include non-business days if true
     * @param bool $inclusive decide whether we should expand the days until the week start or not
     *
     * @return WeekInterval[]
     * @throws Exception
     */
    public function getWeeksBreak(DateTime $startDate, DateTime $endDate, $full = false, $inclusive = false)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $output = [];

        if ($inclusive) {
            $this->addPaddingDays();
        }

        foreach ($this->makeIntervals($full) as $key => $value) {
            $output[$key] = new WeekInterval($value['start'], $value['end']);
        }

        return $output;
    }

    /**
     * Returns a week interval, based on year week and year month
     * Both parameters are necessary because a given week can have
     * days from two different months, and in this case, we might
     * want to pull days from one month, another month, or even
     * the entire week, regardless the month
     *
     * @param string $yearWeek
     * @param string $yearMonth
     * @param bool $full
     * @param bool $inclusive
     * @return WeekInterval
     * @throws Exception
     */
    public function unwrapWeek(
        string $yearWeek,
        string $yearMonth,
        bool $full = false,
        bool $inclusive = false
    ): WeekInterval {
        $aux = str_split($yearMonth, 4);
        $aux[] = '01';
        $objStart = new DateTime(implode('-', $aux));

        $startDate = new DateTime(date('Y-m-01', $objStart->getTimestamp()));
        $endDate = new DateTime(date('Y-m-t', $objStart->getTimestamp()));

        $weeks = $this->getWeeksBreak($startDate, $endDate, $full, $inclusive);

        foreach ($weeks as $week) {
            if ($week->getYearWeek() === $yearWeek) {
                return $week;
            }
        }
    }

    /**
     * @throws Exception
     */
    private function adjustDayLightSavingsTime() :void
    {
        $oneHour = new DateInterval('PT1H');

        if ($this->startDate->format('h') === '01') {
            $this->startDate->sub($oneHour);
        }

        if ($this->startDate->format('h') === '23') {
            $this->startDate->add($oneHour);
        }
    }

    private function addPaddingDays()
    {
        if ($this->startDate->format('w') !== $this->config->getWeeksStartsOn()) {
            $this->startDate->setISODate(
                (int) $this->startDate->format('o'),
                (int) $this->startDate->format('W'),
                (int) $this->config->getWeeksStartsOn()->getValue()
            );
        }

        if ($this->endDate->format('w') !== $this->config->getWeeksEndsOn()) {
            $this->endDate->setISODate(
                (int) $this->endDate->format('o'),
                (int) $this->endDate->format('W'),
                (int) $this->config->getWeeksEndsOn()->getValue()
            );
        }
    }

    /**
     * @param bool $full
     * @return array
     *
     * @throws Exception
     */
    private function makeIntervals(bool $full): array
    {
        if ($full) {
            return $this->makeFull();
        }

        return $this->makeBusinessOnly();
    }

    /**
     * @return array
     * @throws Exception
     */
    private function makeFull() :array
    {
        $output = [];
        $index = 0;
        $isOpen = false;
        $oneDay = new DateInterval('P1D');

        while ($this->startDate <= $this->endDate) {
            $str = $this->startDate->format('w');

            if (!$isOpen && ($str !== $this->config->getWeeksEndsOn()->getValue())) {
                $output[$index]['start'] = clone $this->startDate;
                $isOpen = true;
            }

            if ($isOpen && ($str === $this->config->getWeeksEndsOn()->getValue())) {
                $output[$index]['end'] = clone $this->startDate;
                $isOpen = false;
                $index++;
            }

            if ($isOpen && ($this->startDate == $this->endDate)) {
                $output[$index]['end'] = clone $this->startDate;
                $isOpen = false;
            }

            $this->startDate->add($oneDay);
            $this->adjustDayLightSavingsTime();
        }

        return $output;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function makeBusinessOnly() : array
    {
        $output = [];
        $index = 0;
        $isOpen = false;
        $oneDay = new DateInterval('P1D');

        while ($this->startDate <= $this->endDate) {
            $str = $this->startDate->format('w');

            if (!$isOpen && $this->isInRange($str)) {
                $output[$index]['start'] = clone $this->startDate;
                $isOpen = true;
            }

            if ($isOpen && !$this->isInRange($str)) {
                $aux = clone $this->startDate;
                $aux->sub($oneDay);
                $output[$index]['end'] = clone $aux;
                $isOpen = false;
                $index++;
            }

            if ($isOpen && ($this->startDate == $this->endDate)) {
                $output[$index]['end'] = clone $this->startDate;
                $isOpen = false;
            }

            $this->startDate->add($oneDay);
            $this->adjustDayLightSavingsTime();
        }

        return $output;
    }

    /**
     * @param string $day
     * @return bool
     */
    private function isInRange($day) :bool
    {
        foreach ($this->config->getBusinessDays() as $businessDay) {
            if ($businessDay->getValue() === $day) {
                return true;
            }
        }

        return false;
    }
}
