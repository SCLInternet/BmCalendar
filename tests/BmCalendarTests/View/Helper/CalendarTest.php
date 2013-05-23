<?php

namespace BmCalendarTests\View\Helper;

use BmCalendar\View\Helper\Calendar;

/**
 * Test for the Calendar view helper.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class CalendarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The instance to be tested.
     *
     * @var Calendar
     */
    protected $helper;

    /**
     * A mock view.
     *
     * @var Zend\View\Renderer\RendererInterface
     */
    protected $view;

    /**
     * Prepare the object to test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->helper = new Calendar();

        $this->view = $this->getMock('Zend\View\Renderer\PhpRenderer');

        $this->helper->setView($this->view);
    }

    /**
     * Tests the partial value is stored.
     *
     * @covers BmCalendar\View\Helper\Calendar::setPartial
     * @covers BmCalendar\View\Helper\Calendar::getPartial
     *
     * @return void
     */
    public function testSetPartial()
    {
        $partialName = 'test-partial';

        $this->helper->setPartial($partialName);

        $this->assertEquals($partialName, $this->helper->getPartial(), 'The partial name was not saved.');
    }

    /**
     * Test that the calendar to be used it set.
     *
     * @covers BmCalendar\View\Helper\Calendar::__invoke
     * @covers BmCalendar\View\Helper\Calendar::getCalendar
     * @depends testSetPartial
     *
     * @return void
     */
    public function testSetCalendar()
    {
        $calendar = $this->getMock('BmCalendar\Calendar');

        $this->helper->setPartial('xxx');

        $result = $this->helper->__invoke($calendar);

        $this->assertEquals($this->helper, $result, 'The helper didn\'t return itself.');

        $this->assertEquals($calendar, $this->helper->getCalendar(), 'The calendar has not been saved in the view helper');

        $this->assertNull($this->helper->getPartial(), 'Partial wasn\'t reset.');
    }

    /**
     * Test the actual rendering of the calendar.
     *
     * @covers BmCalendar\View\Helper\Calendar::showMonth
     * @depends testSetPartial
     * @depends testSetCalendar
     *
     * @return void
     */
    public function testShowMonthWithPartial()
    {
        $output = 'THE_CALENDAR';
        $partialName = 'the-partial';
        $year = 2013;
        $month = 7;

        $calendar = $this->getMock('BmCalendar\Calendar');
        $params = array(
            'calendar' => $calendar,
            'year'     => $year,
            'month'    => $month,
        );

        $partial = $this->getMock('Zend\View\Helper\Partial');

        $this->view->expects($this->once())
                   ->method('plugin')
                   ->with($this->equalTo('partial'))
                   ->will($this->returnValue($partial));

        $partial->expects($this->once())
                ->method('__invoke')
                ->with($this->equalTo($partialName), $this->equalTo($params))
                ->will($this->returnValue($output));

        $this->helper->__invoke($calendar);
        $this->helper->setPartial($partialName);

        $result = $this->helper->showMonth($year, $month);

        $this->assertEquals($output, $result, 'The output didn\'t match.');
    }
}
