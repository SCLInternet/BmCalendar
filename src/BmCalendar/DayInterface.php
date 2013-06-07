<?php

namespace BmCalendar;

/**
 * DayInterface
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface DayInterface
{
    const MONDAY    = 1;
    const TUESDAY   = 2;
    const WEDNESDAY = 3;
    const THURSDAY  = 4;
    const FRIDAY    = 5;
    const SATURDAY  = 6;
    const SUNDAY    = 7;

    /**
     * __construct
     *
     * @param  int   $day
     * @param  Month $month
     */
    public function __construct(Month $month, $day);

    /**
     * Return a specific state by uid.
     *
     * @param  string $uid
     * @return DayStateInterface|null NULL if the date doesn't have a state with the request uid.
     */
    public function getState($uid);

    /**
     * Returns a list of states for this day.
     *
     * @return DayStateInterface[]
     */
    public function getStates();

    /**
     * Gets the value of action
     *
     * @return string
     */
    public function getAction();

    /**
     * Returns the month this day belongs to.
     *
     * @return Month
     */
    public function getMonth();

    /**
     * Returns the day of the week.
     *
     * @return int
     */
    public function dayOfWeek();

    /**
     * Return the day number.
     *
     * @return int
     */
    public function value();

    /**
     * Convert the day number to a string.
     *
     * @return string
     */
    public function __toString();
}
