<?php

namespace BmCalendar;

/**
 * Implement this with different states to be attached to Days.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface DayStateInterface
{
    /**
     * A unique identifier for this state.
     *
     * @return string
     */
    public static function uid();
}
