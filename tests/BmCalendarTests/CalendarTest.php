<?php

namespace BmCalendarTest;

use BmCalendar\Calendar;
use BmCalendar\Day;
use BmCalendar\DayProviderInterface;
use BmCalendar\Month;
use BmCalendar\Year;

/**
 * Tests for {@see BmCalendar\Calendar}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class CalendarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Mock day provider.
     *
     * @var DayProviderInterface
     */
    protected $dayProvider;

    /**
     * The object used for testing.
     *
     * @var Calendar
     */
    protected $calendar;

    /**
     * Prepare the object to be tested.
     */
    protected function setUp()
    {
        $this->dayProvider = $this->getMock('BmCalendar\DayProviderInterface');

        $this->calendar = new Calendar($this->dayProvider);
    }

    /**
     * Check that the Year returned contains the correct value.
     *
     * @covers BmCalendar\Calendar::getYear
     *
     * @return void
     */
    public function testGetYear()
    {
        $yearNo = 2013;

        $year = $this->calendar->getYear($yearNo);

        $this->assertEquals($yearNo, $year->value(), 'The year object is incorrect.');
    }

    /**
     * Check that the Month returned contains the correct value.
     *
     * @covers BmCalendar\Calendar::getMonth
     * @covers BmCalendar\Calendar::yearOrYearNo
     *
     * @return void
     */
    public function testGetMonthFromScalarYear()
    {
        $yearNo = 2013;
        $monthNo = 5;

        $month = $this->calendar->getMonth($yearNo, $monthNo);
        $year = $month->getYear();

        $this->assertEquals($yearNo, $year->value(), 'The year object is incorrect.');
        $this->assertEquals($monthNo, $month->value(), 'The month object is incorrect.');
    }

    /**
     * Check that the Month returned contains the correct value.
     *
     * @covers BmCalendar\Calendar::getMonth
     *
     * @return void
     */
    public function testGetMonthFromYearObject()
    {
        $year = new Year(2013);
        $monthNo = 5;

        $month = $this->calendar->getMonth($year, $monthNo);

        $this->assertEquals($year->value(), $month->getYear()->value(), 'The year object is incorrect.');
        $this->assertEquals($monthNo, $month->value(), 'The month object is incorrect.');
    }

    /**
     * Check that an exception is throw if bad parameters are given.
     *
     * @covers BmCalendar\Calendar::yearOrYearNo
     * @covers BmCalendar\Calendar::getMonth
     * @expectedException BmCalendar\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testGetMonthWithBadYearObject()
    {
        $year = new \stdClass();
        $monthNo = 5;

        $month = $this->calendar->getMonth($year, $monthNo);
    }

    /**
     * Test for the getMonths method()
     *
     * @covers BmCalendar\Calendar::getMonths
     * @covers BmCalendar\Calendar::yearOrYearNo
     *
     * @return void
     */
    public function testGetMonths()
    {
        $year = new Year(2015);

        $months = $this->calendar->getMonths($year);

        $this->assertCount(12, $months, 'There must be 12 months in a year.');

        $monthNo = 1;
        foreach ($months as $month) {
            $this->assertEquals($monthNo, $month->value(), 'Month value is incorrect.');

            $this->assertEquals($year, $month->getYear(), 'The month is from the wrong year');

            $monthNo++;
        }
    }

    /**
     * Test getDay with a good day provider.
     *
     * @covers BmCalendar\Calendar::getDay
     *
     * @return void
     */
    public function testGetDayWithGoodDayProvider()
    {
        $requestedDay = 13;

        $month = $this->calendar->getMonth(1999, 3);
        $day = new Day($month, $requestedDay);

        $this->dayProvider
             ->expects($this->once())
             ->method('createDay')
             ->with($this->equalTo($month), $this->equalTo($requestedDay))
             ->will($this->returnValue($day));

        $this->assertEquals($day, $this->calendar->getDay($month, $requestedDay));
    }

    /**
     * Test that when a day is created with a value which doesn't match the requested
     * day then an exception is thrown.
     *
     * @covers BmCalendar\Calendar::getDay
     * @expectedException BmCalendar\Exception\DomainException
     *
     * @return void
     */
    public function testGetDayWithValueNotMatchingRequestedDay()
    {
        $requestedDay = 13;

        $month = $this->calendar->getMonth(1999, 3);
        $day = new Day($month, 22);

        $this->dayProvider
             ->expects($this->once())
             ->method('createDay')
             ->with($this->equalTo($month), $this->equalTo($requestedDay))
             ->will($this->returnValue($day));

        $this->calendar->getDay($month, $requestedDay);
    }

    /**
     * Test that when a day is created with a value which doesn't match the requested
     * day then an exception is thrown.
     *
     * @covers BmCalendar\Calendar::getDay
     * @expectedException BmCalendar\Exception\DomainException
     *
     * @return void
     */
    public function testGetDayWithWrongMonthValue()
    {
        $requestedDay = 13;

        $month = $this->calendar->getMonth(1999, 3);

        $day = new Day(new Month(new Year(2000), 1), $requestedDay);

        $this->dayProvider
             ->expects($this->once())
             ->method('createDay')
             ->with($this->equalTo($month), $this->equalTo($requestedDay))
             ->will($this->returnValue($day));

        $this->calendar->getDay($month, $requestedDay);
    }

    /**
     * Test getDay with a bad day provider.
     *
     * @covers BmCalendar\Calendar::getDay
     * @expectedException BmCalendar\Exception\DomainException
     *
     * @return void
     */
    public function testGetDayWithBadDayProvider()
    {
        $requestedDay = 13;

        $month = $this->calendar->getMonth(1999, 3);

        $this->dayProvider
             ->expects($this->once())
             ->method('createDay')
             ->will($this->returnValue(new \stdClass()));

        $day = $this->calendar->getDay($month, 13);
    }

    /**
     * Test getDay with no day provider.
     *
     * @covers BmCalendar\Calendar::getDay
     *
     * @return void
     */
    public function testGetDayWithNoDayProvider()
    {
        $requestedDay = 25;

        $calendar = new Calendar();

        $month = $calendar->getMonth(2001, 3);

        $day = $calendar->getDay($month, 25);

        $this->assertInstanceOf('BmCalendar\DayInterface', $day);
        $this->assertEquals($month, $day->getMonth());
        $this->assertEquals(25, $day->value());
    }

    /**
     * testGetDays
     *
     * @covers BmCalendar\Calendar::getDays
     *
     * @return void
     */
    public function testGetDays()
    {
        $month = new Month(new Year(2013), 3);

        $calendar = new Calendar();

        $days = $calendar->getDays($month);

        $this->assertCount(31, $days);

        $dayNo = 1;
        foreach ($days as $day) {
            $this->assertInstanceOf('BmCalendar\DayInterface', $days[$dayNo]);
            $this->assertEquals($dayNo, $day->value());
            $this->assertEquals($month, $day->getMonth());
            $dayNo++;
        }
    }
}
