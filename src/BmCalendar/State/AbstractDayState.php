<?php

namespace BmCalendar\State;

/**
 * Abstract day state.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
abstract class AbstractDayState implements DayStateInterface
{
    /**
     * Return the name of the class as the type of the DayState class.
     *
     * @return string
     */
    public static function type()
    {
        return get_called_class();
    }
}
