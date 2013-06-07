<?php

namespace BmCalendarTests;

use BmCalendar\Day;
use BmCalendar\Month;
use BmCalendar\Year;
use BmCalendarTests\State;

/**
 * Unit tests for {@see BmCalendar\Day}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class DayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Check that if the day of the month is less than 1 an exception is thrown.
     *
     * @covers BmCalendar\Day::__construct
     * @expectedException BmCalendar\Exception\DomainException
     *
     * @return void
     */
    public function testDayValueTooLow()
    {
        $day = new Day(new Month(new Year(2013), 5), 0);
    }

    /**
     * Check that if the day of the month is greater than the number of days in
     * the month an exception is thrown.
     *
     * @covers BmCalendar\Day::__construct
     * @expectedException BmCalendar\Exception\DomainException
     *
     * @return void
     */
    public function testDayValueTooHigh()
    {
        $day = new Day(new Month(new Year(2013), 4), 31);
    }

    /**
     * Test getting the values back from a Day object.
     *
     * @covers BmCalendar\Day::value
     * @covers BmCalendar\Day::__construct
     * @covers BmCalendar\Day::__toString
     */
    public function testValue()
    {
        $month =  new Month(new Year(2013), Month::JANUARY);
        $day = new Day($month, 25);

        $this->assertEquals($month, $day->getMonth(), 'The month object is incorrect.');

        $this->assertEquals(25, $day->value(), 'The value of the day is incorrect.');

        $this->assertEquals('25', (string) $day, 'String representation of the day is wrong.');
    }

    /**
     * Tests that the day of the week is correctly calculated.
     *
     * @covers BmCalendar\Day::dayOfWeek
     */
    public function testDayOfWeek()
    {
        $today = new \DateTime();

        $year  = (int) $today->format('Y');
        $month = (int) $today->format('m');
        $day   = (int) $today->format('j');

        $dayOfWeek = $today->format('N');

        $dayObject = new Day(new Month(new Year($year), $month), $day);

        $this->assertEquals($dayOfWeek, $dayObject->dayOfWeek(), 'The day of the week doesn\'t match.');
    }

    /**
     * Test that states are added to a Day object correctly.
     *
     * @covers BmCalendar\Day::addState
     * @covers BmCalendar\Day::getStates
     *
     * @return void
     */
    public function testGetStates()
    {
        $state1 = new State\MockStateA();
        $state2 = new State\MockStateB();

        $day = new Day(new Month(new Year(2013), 6), 17);

        $day->addState($state1)
            ->addState($state2);

        $this->assertEquals(
            array(State\MockStateA::uid() => $state1, State\MockStateB::uid() => $state2),
            $day->getStates(),
            'Get states didn\'t return all the states'
        );
    }

    /**
     * Test the getState method.
     *
     * @covers BmCalendar\Day::getState
     * @covers BmCalendar\Day::addState
     *
     * @return void
     */
    public function testGetState()
    {
        $state = new State\MockStateA();

        $day = new Day(new Month(new Year(2013), 6), 17);

        $day->addState($state);

        $this->assertEquals(
            $state,
            $day->getState(State\MockStateA::uid()),
            'Failed to get the requested state.'
        );

        $this->assertNull(
            $day->getState(State\MockStateB::uid()),
            'Get state which doesn\'t exist didn\'t return null.'
        );
    }

    /**
     * testAction
     *
     * @covers BmCalendar\Day::setAction
     * @covers BmCalendar\Day::getAction
     *
     * @return void
     */
    public function testGetSetAction()
    {
        $action = 'the_action';

        $day = new Day(new Month(new Year(2013), 6), 17);

        $result = $day->setAction($action);

        $this->assertEquals($day, $result, 'Interface is not fluent.');

        $result = $day->getAction();

        $this->assertEquals($action, $result, 'Incorrect action.');
    }

    /**
     * testAction
     *
     * @covers BmCalendar\Day::__construct
     * @covers BmCalendar\Day::getMonth
     *
     * @return void
     */
    public function testGetMonth()
    {
        $month = new Month(new Year(2013), 6);

        $day = new Day($month, 17);

        $result = $day->getMonth();

        $this->assertEquals($month, $result, 'Incorrect month.');
    }
}
