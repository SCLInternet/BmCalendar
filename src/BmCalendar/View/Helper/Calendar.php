<?php
/**
  * BmCalendar Module (https://github.com/SCLInternet/BmCalendar)
  *
  * @link https://github.com/SCLInternet/BmCalendar for the canonical source repository
  * @license http://opensource.org/licenses/MIT The MIT License (MIT)
  */

namespace BmCalendar\View\Helper;

use BmCalendar\Calendar as CalendarObject;
use BmCalendar\Day;
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
                'year'     => $year,
                'month'    => $month,
            );

            return $partial($this->partial, $params);
        }

        $this->getRenderer()->setCalendar($this->calendar);

        return $this->getRenderer()->renderMonth($year, $month);
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
