<?php

namespace BmCalendar\View\Helper;

use BmCalendar\Calendar as CalendarObject;
use Zend\View\Helper\AbstractHelper;

/**
 * View helper to render the calendar in a view.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Calendar extends AbstractHelper
{
    /**
     * The {@see CalendarObject} object to be rendered.
     *
     * @var CalendarObject
     */
    protected $calendar;

    /**
     * The name of the template used to render the calendar.
     *
     * @var null|string
     */
    protected $partial;

    /**
     * Returns the {@see CalendarObject} to be rendered.
     *
     * @return CalendarObject
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * Sets the value of partial
     *
     * @param  string $partial
     * @return self
     */
    public function setPartial($partial)
    {
        $this->partial = (string) $partial;
        return $this;
    }

    /**
     * Gets the value of partial
     *
     * @return string
     */
    public function getPartial()
    {
        return $this->partial;
    }

    public function showMonth($year, $month)
    {
        if (null !== $this->partial) {
            $partial = $this->view->plugin('partial');

            $params = array(
                'calendar' => $this->calendar,
                'year'     => $year,
                'month'    => $month,
            );

            return $partial($this->partial, $params);
        }
    }

    /**
     * The entry point for the view helper.
     *
     * This method saves the {@see CalendarObject} to which is to be rendered
     * and then returns itself to expose it's public interface.
     *
     * @param  CalendarObject $calendar
     * @return self
     */
    public function __invoke(CalendarObject $calendar)
    {
        $this->calendar = $calendar;
        $this->partial = null;
        return $this;
    }
}
