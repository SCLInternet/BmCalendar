<?php

namespace BmCalendarTests;

use BmCalendar\Day;
use BmCalendar\Month;
use BmCalendar\Year;

/**
 * Unit tests for {@see BmCalendar\Month}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class MonthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Give a valid Year and an invalid Month
     * Which a Month object is created
     * Then throw a DomainException
     *
     * @covers BmCalendar\Month::__construct
     * @expectedException BmCalendar\Exception\DomainException
     */
    public function testInvalidMonth()
    {
        $year = new Year(2013);
        $month = new Month($year, 15);
    }

    /**
     * Test getting the values back from a Month object.
     *
     * @covers BmCalendar\Month::value
     * @covers BmCalendar\Month::__toString
     *
     * @return void
     */
    public function testValue()
    {
        $year = new Year(2015);
        $month = new Month($year, 5);

        $this->assertEquals($year, $month->getYear(), 'Year objects don\'t match.');

        $this->assertEquals(5, $month->value(), 'Month value is incorrect.');

        $this->assertEquals('5', (string) $month, 'String representation of month is incorrect.');
    }

    /**
     * testGetNumberOfDays
     *
     * @dataProvider numberOfDaysProvider
     * @covers BmCalendar\Month::numberOfDays
     *
     * @param  int $yearNo
     * @param  int $monthNo
     * @param  int $noDays
     * @return void
     */
    public function testNumberOfDays($yearNo, $monthNo, $noDays)
    {
        // Non-leap year
        $year = new Year($yearNo);

        $month = new Month($year, $monthNo);

        $this->assertEquals($noDays, $month->numberOfDays(), "Number of days for $yearNo-$monthNo is incorrect.");
    }

    /**
     * Test startDay method.
     *
     * @covers BmCalendar\Month::startDay
     *
     * @return void
     */
    public function testStartDay()
    {
        $month = new Month(new Year(2013), 5);

        $this->assertEquals(Day::WEDNESDAY, $month->startDay(), 'The starting day of the month is wrong.');
    }

    /**
     * Data provider for numver of days in a month.
     *
     * @return array
     */
    public function numberOfDaysProvider()
    {
        return array(
            array('2003', Month::JANUARY, 31),
            array('2003', Month::FEBRURY, 28),
            array('2004', Month::FEBRURY, 29),
            array('2003', Month::MARCH, 31),
            array('2003', Month::APRIL, 30),
            array('2003', Month::MAY, 31),
            array('2003', Month::JUNE, 30),
            array('2003', Month::JULY, 31),
            array('2003', Month::AUGUST, 31),
            array('2003', Month::SEPTEMBER, 30),
            array('2003', Month::OCTOBER, 31),
            array('2003', Month::NOVEMBER, 30),
            array('2003', Month::DECEMBER, 31),
        );
    }
}
