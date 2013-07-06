<?php
/**
  * BmCalendar Module (https://github.com/SCLInternet/BmCalendar)
  *
  * @link https://github.com/SCLInternet/BmCalendar for the canonical source repository
  * @license http://opensource.org/licenses/MIT The MIT License (MIT)
  */

namespace BmCalendar\View\Helper;

use BmCalendar\Calendar as CalendarObject;
use BmCalendar\DayInterface;
use BmCalendar\Day;
use BmCalendar\Exception\OutOfRangeException;
use BmCalendar\Month;
use BmCalendar\Renderer\CalendarRendererInterface as Renderer;
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
     * Class to generate HTML version of the calendar.
     *
     * @var Renderer
     */
    protected $renderer;

    /**
     * The day to start a week on.
     *
     * @var mixed
     */
    protected $startDay = DayInterface::MONDAY;

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
     * Set which day to display as the starting day of the week.
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
     * Sets which day of the week the rendering will begin on.
     *
     * @return void
     */
    public function getStartDay()
    {
        return $this->startDay;
    }

    /**
     * Set the renderer to be used.
     *
     * @param Renderer $renderer
     *
     * @return self
     * @todo Accept closure to generate renderer.
     */
    public function setRenderer(Renderer $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * Gets the value of renderer
     *
     * @return Renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
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
                'startDay' => $this->startDay,
                'year'     => $year,
                'month'    => $month,
            );

            return $partial($this->partial, $params);
        }

        $renderer = $this->getRenderer();

        $renderer->setCalendar($this->calendar);
        $renderer->setStartDay($this->startDay);

        return $renderer->renderMonth($year, $month);
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
