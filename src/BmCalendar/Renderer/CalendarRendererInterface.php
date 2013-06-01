<?php

namespace BmCalendar\Renderer;

use BmCalendar\Calendar;

/**
 * Interface for rendering a Calendar.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface CalendarRendererInterface
{
    /**
     * Set the calendar to be renderer.
     *
     * @param  Calendar $calendar
     * @return self
     */
    public function setCalendar(Calendar $calendar);

    /**
     * Render a month table.
     *
     * @param  int $yearNo
     * @param  int $monthNo
     * @return string
     */
    public function renderMonth($year, $month);
}
