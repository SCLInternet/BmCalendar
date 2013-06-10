<?php

namespace BmCalendar\State;

/**
 * Implement this with different states to be attached to Days.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface DayStateInterface
{
    /**
     * A unique identifier for grouping related states together.
     *
     * @return string
     */
    public static function type();
}
