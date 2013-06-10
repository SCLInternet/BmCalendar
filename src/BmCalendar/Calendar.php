<?php
/**
  * BmCalendar Module (https://github.com/SCLInternet/BmCalendar)
  *
  * @link https://github.com/SCLInternet/BmCalendar for the canonical source repository
  * @license http://opensource.org/licenses/MIT The MIT License (MIT)
  */

namespace BmCalendar;

use BmCalendar\Exception\InvalidArgumentException;
use BmCalendar\Exception\DomainException;

/**
 * The main Calendar class for this module.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Calendar
{
    /**
     * The system which configures day objects.
     *
     * @var DayProviderInterface
     */
    protected $dayProvider;

    /**
     * Initialise the class.
     *
     * @param  DayProviderInterface $dayProvider
     */
    public function __construct(DayProviderInterface $dayProvider = null)
    {
        $this->dayProvider = $dayProvider;
    }

    /**
     * Returns a year object for the given year number.
     *
     * @param  int $yearNo
     * @return Year
     */
    public function getYear($yearNo)
    {
        return new Year($yearNo);
    }

    /**
     * Checks the type of the provided parameter and returns the appropriate Year.
     *
     * @param  int|Year $year
     * @return Year
     * @throws InvalidArgumentException
     */
    protected function yearOrYearNo($year)
    {
        if (is_scalar($year)) {
            return $this->getYear($year);
        }

        if (!$year instanceof Year) {
            throw new InvalidArgumentException(
                '$year must be an instance of BmCalendar\Year; got "' . get_class($year) . '"'
            );
        }

        return $year;
    }

    /**
     * Returns the appropriate Month object.
     *
     * @param  int|Year $year
     * @param  int      $monthNo
     * @return Month
     */
    public function getMonth($year, $monthNo)
    {
        $year = $this->yearOrYearNo($year);

        return new Month($year, $monthNo);
    }

    /**
     * Get this months for the given year.
     *
     * @param  int|Year $year
     * @return Month[]
     */
    public function getMonths($year)
    {
        $year = $this->yearOrYearNo($year);

        $months = array();

        for ($monthNo = 1; $monthNo <= 12; $monthNo++) {
            $months[$monthNo] = new Month($year, $monthNo);
        }

        return $months;
    }

    /**
     * Returns the requested day object.
     *
     * @param  Month $month
     * @param  int   $dayNo
     * @return DayInterface
     */
    public function getDay(Month $month, $dayNo)
    {
        $dayNo = (int) $dayNo;

        if (null === $this->dayProvider) {
            return new Day($month, $dayNo);
        }

        $day = $this->dayProvider->createDay($month, $dayNo);

        if (!$day instanceof DayInterface) {
            throw new DomainException(
                '$day must be instance of BmCalendar\DayInterface; got '
                . is_object($day) ? get_class($day) : gettype($day)
            );
        }

        if ($day->value() !== $dayNo) {
            throw new DomainException(
                "The value of the day is wrong, it should be $dayNo but is actually "
                . $day->value()
            );
        }

        if ($day->getMonth() !== $month) {
            throw new DomainException(
                'The day returned from the day provider contains the wrong Month.'
            );
        }

        return $day;
    }

    /**
     * Returns all the days for the given month.
     *
     * @param Month $month
     * @return DayInterface[]
     */
    public function getDays(Month $month)
    {
        $days = array();

        for ($dayNo = 1; $dayNo <= $month->numberOfDays(); $dayNo++) {
            $days[$dayNo] = $this->getDay($month, $dayNo);
        }

        return $days;
    }
}
