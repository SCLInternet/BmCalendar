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
use BmCalendar\Exception\OutOfRangeException;
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
     * The names of the 7 days of the week.
     *
     * @var string[]
     */
    protected static $dayNames = array(
        DayInterface::MONDAY    => 'Mon',
        DayInterface::TUESDAY   => 'Tue',
        DayInterface::WEDNESDAY => 'Wed',
        DayInterface::THURSDAY  => 'Thu',
        DayInterface::FRIDAY    => 'Fri',
        DayInterface::SATURDAY  => 'Sat',
        DayInterface::SUNDAY    => 'Sun',
    );

    /**
     * The calendar.
     *
     * @var Calendar
     */
    protected $calendar;

    /**
     * The day to start a week on.
     *
     * @var mixed
     */
    protected $startDay = DayInterface::MONDAY;

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
     * {@inheritDoc}
     *
     * @param  int  $startDay
     * @return self
     */
    public function setStartDay($startDay)
    {
        $startDay = (int) $startDay;

        if ($startDay < 1 || $startDay > 7) {
            throw new OutOfRangeException(
                "'$startDay' is an invalid value for \$startDay in '"
                . __METHOD__
            );
        }

        $this->startDay = $startDay;

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

        // Display the headings for the days of the week.
        for ($column = 0; $column < 7; $column++) {
            $day = ($column + $this->startDay - 1) % 7 + 1;

            if (DayInterface::SATURDAY === $day || DayInterface::SUNDAY === $day) {
                $output .= '<th class="' . $weekendClass . '">';
            } else {
                $output .= '<th>';
            }
            $output .= self::$dayNames[$day];
            $output .=  '</th>';
        }

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

        if (DayInterface::SATURDAY === $dayOfWeek || DayInterface::SUNDAY === $dayOfWeek) {
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

        $blankCells = ($month->startDay() - $this->startDay + 7) % 7;

        while ($column < $blankCells) {
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
