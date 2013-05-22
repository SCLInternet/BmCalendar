<?php

namespace BmCalendar;

use BmCalendar\Exception\DomainException;

/**
 * Month
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Month
{
    const JANUARY   = 1;
    const FEBRURY   = 2;
    const MARCH     = 3;
    const APRIL     = 4;
    const MAY       = 5;
    const JUNE      = 6;
    const JULY      = 7;
    const AUGUST    = 8;
    const SEPTEMBER = 9;
    const OCTOBER   = 10;
    const NOVEMBER  = 11;
    const DECEMBER  = 12;

    /**
     * The month this represents.
     *
     * @var int
     */
    protected $month;

    /**
     * The year this month is from.
     *
     * @var Year
     */
    protected $year;

    /**
     * __construct
     *
     * @param  Year $year
     * @param  int  $month
     * @throws DomainException
     */
    public function __construct(Year $year, $month)
    {
        $month = (int) $month;

        if ($month < 1 || $month > 12) {
            throw new DomainException('$month must be between 1 and 12; got "' . $month .'"');
        }

        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Gets the number of days in this month.
     *
     * @return int
     */
    public function numberOfDays()
    {
        if (self::APRIL === $this->month
            || self::JUNE === $this->month
            || self::SEPTEMBER === $this->month
            || self::NOVEMBER === $this->month
        ) {
            return 30;
        }

        if (self::FEBRURY === $this->month) {
            return $this->year->isLeap() ? 29 : 28;
        }

        return 31;
    }

    /**
     * Returns the day of the week that the 1st of this month is on.
     *
     * @return int
     */
    public function startDay()
    {
        $dateString = sprintf('%04d-%02d-01', $this->year->value(), $this->month);

        $datetime = new \DateTime($dateString);

        return (int) $datetime->format('N');
    }

    /**
     * Returns the year object that this month belongs to.
     *
     * @return void
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Return the month number.
     *
     * @return int
     */
    public function value()
    {
        return $this->month;
    }

    /**
     * Convert the month number to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->month;
    }
}
