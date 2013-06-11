<?php
/**
  * BmCalendar Module (https://github.com/SCLInternet/BmCalendar)
  *
  * @link https://github.com/SCLInternet/BmCalendar for the canonical source repository
  * @license http://opensource.org/licenses/MIT The MIT License (MIT)
  */

namespace BmCalendar;

use BmCalendar\Exception\OutOfRangeException;
use BmCalendar\State\DayStateInterface;

/**
 * Day
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Day implements DayInterface
{
    /**
     * The day this class represents.
     *
     * @var int
     */
    protected $day;

    /**
     * The month this day belongs to.
     *
     * @var Month
     */
    protected $month;

    /**
     * The states this day is in.
     *
     * @var DayStateInterface[]
     */
    protected $states = array();

    /**
     * The URL to go to if this day is clicked
     *
     * @var string
     */
    protected $action;

    /**
     * {@inheritDoc}
     *
     * @param  int   $day
     * @param  Month $month
     */
    public function __construct(Month $month, $day)
    {
        $day = (int) $day;

        if ($day < 1 || $day > $month->numberOfDays()) {
            throw new OutOfRangeException('$day value of "' . $day . '" is out of range.');
        }

        $this->day = $day;
        $this->month = $month;
    }

    /**
     * Add a state to this day.
     *
     * @param  DayStateInterface $state
     * @return self
     */
    public function addState(DayStateInterface $state)
    {
        $this->states[$state::type()] = $state;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param  string $type
     * @return DayStateInterface|null NULL if the date doesn't have a state with the request type.
     */
    public function getState($type)
    {
        if (!isset($this->states[$type])) {
            return null;
        }

        return $this->states[$type];
    }

    /**
     * {@inheritDoc}
     *
     * @return DayStateInterface[]
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * Sets the value of action
     *
     * @param  string $action
     * @return self
     */
    public function setAction($action)
    {
        $this->action = (string) $action;
        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * {@inheritDoc}
     *
     * @return Month
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function dayOfWeek()
    {
        $dateString = sprintf(
            '%04d-%02d-%02d',
            $this->month->getYear()->value(),
            $this->month->value(),
            $this->day
        );

        $datetime = new \DateTime($dateString);

        return (int) $datetime->format('N');
    }

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function value()
    {
        return $this->day;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->day;
    }
}
