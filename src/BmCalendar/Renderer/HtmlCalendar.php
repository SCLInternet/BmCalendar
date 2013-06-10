<?php
/**
  * BmCalendar Module (https://github.com/SCLInternet/BmCalendar)
  *
  * @link https://github.com/SCLInternet/BmCalendar for the canonical source repository
  * @license http://opensource.org/licenses/MIT The MIT License (MIT)
  */

namespace BmCalendar\Renderer;

use BmCalendar\Calendar;
use BmCalendar\DayInterface;
use BmCalendar\Month;

/**
 * Class to output the HTML to display a calendar.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class HtmlCalendar implements CalendarRendererInterface
{
    /**
     * The names of the 12 months.
     *
     * @var string[]
     */
    protected static $monthNames = array(
        1  => 'January',
        2  => 'February',
        3  => 'March',
        4  => 'April',
        5  => 'May',
        6  => 'June',
        7  => 'July',
        8  => 'August',
        9  => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    );

    /**
     * The calendar.
     *
     * @var Calendar
     */
    protected $calendar;

    /**
     * {@inheritDoc}
     *
     * @param  Calendar $calendar
     * @return self
     */
    public function setCalendar(Calendar $calendar)
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * Returns the markup for the header of a month table.
     *
     * @param  Month $month
     * @return string
     */
    public function monthTitle(Month $month)
    {
        $weekendClass = 'bm-calendar-weekend';

        $monthName = self::$monthNames[$month->value()];

        $output  = '<thead>';
        $output .= '<tr>';

        $output .= '<th colspan="7" class="bm-calendar-month-title">' . $monthName . '</th>';

        $output .= '</tr><tr>';

        $output .= '<th>Mon</th>';
        $output .= '<th>Tue</th>';
        $output .= '<th>Wed</th>';
        $output .= '<th>Thu</th>';
        $output .= '<th>Fri</th>';
        $output .= '<th class="' . $weekendClass . '">Sat</th>';
        $output .= '<th class="' . $weekendClass . '">Sun</th>';

        $output .= '</tr>';
        $output .= '</thead>';

        return $output;
    }

    /**
     * Returns the markup for a single table cell.
     *
     * @param  DayInterface $day
     * @return string
     */
    public function renderDay(DayInterface $day)
    {
        $classes = array();

        $dayOfWeek = $day->dayOfWeek();

        if (5 === $dayOfWeek || 6 === $dayOfWeek) {
            $classes[] = 'bm-calendar-weekend';
        }

        foreach ($day->getStates() as $state) {
            $classes[] = 'bm-calendar-state-' . $state->type();
        }

        $output  = '<td>';
        if (sizeof($classes)) {
            $output  = '<td class="' . implode(' ', $classes) . '">';
        }

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
    public function renderMonth($year, $month)
    {
        $monthClass   = sprintf('bm-calendar-month-%02d', $month);
        $yearClass    = sprintf('bm-calendar-year-%04d', $year);

        $month = $this->calendar->getMonth($year, $month);
        $days  = $this->calendar->getDays($month);

        $column = 0;

        $output  = '<table class="bm-calendar ' . $monthClass . ' ' . $yearClass .'">';

        $output .= $this->monthTitle($month);

        $output .= '<tbody>';

        $output .= '<tr>';

        while ($column < $month->startDay() - 1) {
            $output .= '<td class="bm-calendar-empty"></td>';
            $column++;
        }

        foreach ($days as $day) {
            if (1 !== $day->value() && 0 === $column) {
                $output .= '</tr><tr>';
            }

            $output .= $this->renderDay($day, $column);

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
