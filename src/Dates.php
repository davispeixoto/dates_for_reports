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
     * Dates constructor.
     *
     * @param DatesConfig $config
     */
    public function __construct(DatesConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param bool $full
     * @param bool $inclusive
     *
     * @return WeekInterval[]
     * @throws Exception
     */
    public function get_weeks_break(DateTime $startDate, DateTime $endDate, $full = false, $inclusive = false)
    {
        $output = [];
        $innerArray = [];
        $isOpen = false;
        $i = 0;

        $interval = new DateInterval('P1D');
        $daylightSavingsTime = new DateInterval('PT1H');

        if ($inclusive) {
            $oldStart = clone $startDate;
            $oldEnd = clone $endDate;

            $aux = new DateTime();

            if ($oldStart->format('w') !== '0') {
                $aux->setISODate($oldStart->format('o'), $oldStart->format('W'), 0);
                $startDate = clone $aux;
            }

            if ($oldEnd->format('w') !== '6') {
                $aux->setISODate($oldEnd->format('o'), $oldEnd->format('W'), 6);
                $endDate = clone $aux;
            }
        }

        $start = clone $startDate;
        $end = clone $endDate;

        while ($start <= $end) {
            $str = (int)$start->format('w');

            if (!$full) {
                if (in_array($str, range(1, 5)) && !$isOpen) {
                    $innerArray[$i]['start'] = clone $start;
                    $isOpen = true;
                }

                if (!in_array($str, range(1, 5)) && $isOpen) {
                    $x = clone $start;
                    $x->sub($interval);
                    $innerArray[$i]['end'] = clone $x;
                    $isOpen = false;
                    $i++;
                }

                if ($start == $end && $isOpen) {
                    $innerArray[$i]['end'] = clone $start;
                    $isOpen = false;
                }
            } else {
                if (($str !== 6) && !$isOpen) {
                    $innerArray[$i]['start'] = clone $start;
                    $isOpen = true;
                }

                if (($str === 6) && $isOpen) {
                    $x = clone $start;
                    $innerArray[$i]['end'] = clone $x;
                    $isOpen = false;
                    $i++;
                }
            }

            if (($start == $end) && $isOpen) {
                $innerArray[$i]['end'] = clone $start;
                $isOpen = false;
            }

            $start->add($interval);

            // fix for day light savings time
            if ($start->format('h') === "01") {
                $start->sub($daylightSavingsTime);
            }

            if ($start->format('h') === "23") {
                $start->add($daylightSavingsTime);
            }
        }

        foreach ($innerArray as $key => $value) {
            $weekInterval = new WeekInterval($value['start'], $value['end']);
            $output[$key] = $weekInterval;
        }

        return $output;
    }

    public function unwrap_week($yearweek, $yearmonth)
    {
        $output = array();

        $x = str_split($yearmonth, 4);
        $x[] = "01";
        $obj_start = DateTime::createFromFormat('Y-m-d', implode('-', $x));

        $start_date = date('Y-m-01', $obj_start->getTimeStamp());
        $end_date = date('Y-m-t', $obj_start->getTimeStamp());

        $weeks = $this->get_weeks_break($start_date, $end_date);
        $weeks_full = $this->get_weeks_break($start_date, $end_date, true);
        $today = new DateTime();

        foreach ($weeks as $key => $value) {
            if ($value['yearweek'] == $yearweek) {
                $output['start'] = $value['start_date'];
                $output['end'] = $value['end_date'];

                $endDateObj = DateTime::createFromFormat('Y-m-d', $output['end']);

                if ($today > $endDateObj) {
                    foreach ($weeks_full as $innerWeek) {
                        if ($value['yearweek'] == $innerWeek['yearweek']) {
                            $output['start'] = $innerWeek['start_date'];
                            $output['end'] = $innerWeek['end_date'];
                            break;
                        }
                    }
                }
                break;
            }
        }

        return $output;
    }
}
