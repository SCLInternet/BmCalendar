<?php
/**
  * BmCalendar Module (https://github.com/SCLInternet/BmCalendar)
  *
  * @link https://github.com/SCLInternet/BmCalendar for the canonical source repository
  * @license http://opensource.org/licenses/MIT The MIT License (MIT)
  */

namespace BmCalendar;

/**
 * Year
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Year
{
    /**
     * The year this class represents.
     *
     * @var int
     */
    protected $year;

    /**
     * __construct
     *
     * @param  int $year
     * @return void
     */
    public function __construct($year)
    {
        $this->year = (int) $year;
    }

    /**
     * Checks if this year is a leap year.
     *
     * @return bool
     */
    public function isLeap()
    {
        if (0 !== $this->year % 4) {
            return false;
        }

        if (0 !== $this->year % 100) {
            return true;
        }

        if (0 !== $this->year % 400) {
            return false;
        }

        return true;
    }

    /**
     * Return the year number.
     *
     * @return int
     */
    public function value()
    {
        return $this->year;
    }

    /**
     * Convert the year number to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->year;
    }
}
