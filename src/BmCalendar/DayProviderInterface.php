<?php

namespace BmCalendar;

/**
 * This interface is used to build classes which setup the attributes of a given day.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface DayProviderInterface
{
    /**
     * Use this to create and set the state of the provided Day.
     *
     * @param  Month $month
     * @param  int   $dayOfMonth
     * @return DayInterface
     */
    public function createDay(Month $month, $dayOfMonth);
}
