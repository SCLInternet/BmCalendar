<?php
/**
  * BmCalendar Module (https://github.com/SCLInternet/BmCalendar)
  *
  * @link https://github.com/SCLInternet/BmCalendar for the canonical source repository
  * @license http://opensource.org/licenses/MIT The MIT License (MIT)
  */

namespace BmCalendarTests;

use BmCalendar\Year;

/**
 * Unit tests for {@see BmCalendar\Year}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class YearTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getting the values back from a Year object.
     *
     * @covers BmCalendar\Year::__construct
     * @covers BmCalendar\Year::value
     * @covers BmCalendar\Year::__toString
     *
     * @return void
     */
    public function testValue()
    {
        $year = new Year(2053);

        $this->assertEquals(2053, $year->value(), 'The value of the year is incorrect.');

        $this->assertEquals('2053', (string) $year, 'String representation of the year is wrong.');
    }

    /**
     * Test the is leap function.
     *
     * @covers BmCalendar\Year::isLeap
     *
     * @return void
     */
    public function testIsLeap()
    {
        $year = new Year(1700);
        $this->assertFalse($year->isLeap(), '1700 was not a leap year.');

        $year = new Year(2000);
        $this->assertTrue($year->isLeap(), '2000 was a leap year.');

        $year = new Year(2004);
        $this->assertTrue($year->isLeap(), '2004 was a leap year.');

        $year = new Year(2002);
        $this->assertFalse($year->isLeap(), '2002 was not a leap year.');

    }
}
