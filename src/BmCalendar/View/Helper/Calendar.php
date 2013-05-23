<?php

namespace BmCalendar\View\Helper;

use BmCalendar\Calendar as CalendarObject;
use BmCalendar\Day;
use BmCalendar\Month;
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

    /**
     * Returns the HTML to display a month.
     *
     * @param  int $year
     * @param  int $month
     * @return void
     */
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

        return $this->renderMonth($year, $month);
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

    /**
     * Returns the markup for the header of a month table.
     *
     * @param  Month $month
     * @return string
     */
    protected function renderMonthTitle(Month $month)
    {
        $weekendClass = 'bm-calendar-weekend';

        $dateString = sprintf(
            '%04d-%02d-01',
            $month->getYear()->value(),
            $month->value()
        );

        $datetime = new \DateTime($dateString);

        $output  = '<thead>';
        $output .= '<tr>';

        $output .= '<th colspan="7">' . $datetime->format('M') . '</th>';

        $output .= '</tr><tr>';

        $output .= '<th>Mon</th>';
        $output .= '<th>Tue</th>';
        $output .= '<th>Wed</th>';
        $output .= '<th>Thu</th>';
        $output .= '<th>Fri</th>';
        $output .= '<th class-"' . $weekendClass . '">Sat</th>';
        $output .= '<th class-"' . $weekendClass . '">Sun</th>';

        $output .= '</tr>';
        $output .= '</thead>';

        return $output;
    }

    /**
     * Returns the markup for a single table cell.
     *
     * @param  Day $day
     * @param  int $column
     * @return string
     */
    protected function renderMonthDay(Day $day, $column)
    {
        $classes = array();

        if (5 === $column || 6 === $column) {
            $classes[] = 'bm-calendar-weekend';
        }

        foreach ($day->getStates() as $state) {
            $classes[] = 'bm-calendar-state-' . $state->getUid();
        }

        $output  = '<td class="' . implode(' ', $classes) . '">';

        if ($day->getAction()) {
            $output .= '<a href="' . htmlentities($day->getAction()) . '">' . $day . '</a>';
        } else {
            $output .= $day;
        }


        $output .= '</td>';

        return $output;
    }

    /**
     * Render a month table internally.
     *
     * @param  int $yearNo
     * @param  int $monthNo
     * @return string
     */
    protected function renderMonth($year, $month)
    {
        $monthClass   = sprintf('bm-calendar-month-%02d', $month);
        $yearClass    = sprintf('bm-calendar-year-%04d', $year);

        $month = $this->calendar->getMonth($year, $month);
        $days  = $this->calendar->getDays($month);

        $column = 0;

        $output  = '<table class="bm-calendar ' . $monthClass . ' ' . $yearClass .'">';

        $output .= $this->renderMonthTitle($month);

        $output .= '<tbody>';

        $output .= '<tr>';

        while ($column < $month->startDay()) {
            $output .= '<td class="bm-calendar-empty"></td>';
            $column++;
        }

        foreach ($days as $day) {
            if (0 === $column) {
                $output .= '</tr></tr>';
            }

            $output .= $this->renderMonthDay($day, $column);

            $column = ($column + 1) % 7;
        }

        while ($column < 7) {
            $output .= '<td class="bm-calendar-empty"></td>';
            $column++;
        }

        $output .= '</tr>';

        $output .= '</tbody>';

        $output .= '</table>';

        return $output;
    }
}
