<?php

namespace BmCalendar;

use BmCalendar\Exception\DomainException;

/**
 * Day
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Day
{
    const MONDAY    = 1;
    const TUESDAY   = 2;
    const WEDNESDAY = 3;
    const THURSDAY  = 4;
    const FRIDAY    = 5;
    const SATURDAY  = 6;
    const SUNDAY    = 7;

    /**
     * The day this class represents.
     *
     * @var int
     */
    protected $day;

    /**
     * The month this day belongs to.
     *
     * @var Month
     */
    protected $month;

    /**
     * __construct
     *
     * @param  int   $day
     * @param  Month $month
     */
    public function __construct(Month $month, $day)
    {
        $day = (int) $day;

        if ($day < 1 || $day > $month->numberOfDays()) {
            throw new DomainException('$day value of "' . $day . '" is out of range.');
        }

        $this->day = $day;
        $this->month = $month;
    }

    /**
     * Returns the month this day belongs to.
     *
     * @return Month
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Returns the day of the week.
     *
     * @return int
     */
    public function dayOfWeek()
    {
        $dateString = sprintf(
            '%04d-%02d-%02d',
            $this->month->getYear()->value(),
            $this->month->value(),
            $this->day
        );

        $datetime = new \DateTime($dateString);

        return (int) $datetime->format('N');
    }

    /**
     * Return the day number.
     *
     * @return int
     */
    public function value()
    {
        return $this->day;
    }

    /**
     * Convert the day number to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->day;
    }
}
