<?php
/**
  * BmCalendar Module (https://github.com/SCLInternet/BmCalendar)
  *
  * @link https://github.com/SCLInternet/BmCalendar for the canonical source repository
  * @license http://opensource.org/licenses/MIT The MIT License (MIT)
  */

namespace BmCalendarTests\View\Helper;

use BmCalendar\DayInterface;
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
     * Test that set start day with a bad value raises and exception.
     *
     * @covers            BmCalendar\View\Helper\Calendar::setStartDay
     * @expectedException BmCalendar\Exception\OutOfRangeException
     *
     * @return void
     */
    public function testSetStartDayWithInvalidValue()
    {
        $this->helper->setStartDay(8);
    }

    /**
     * Test that the getStartDay and setStartDay methods work as expected.
     *
     * @covers BmCalendar\View\Helper\Calendar::setStartDay
     * @covers BmCalendar\View\Helper\Calendar::getStartDay
     *
     * @return void
     */
    public function testSetGetStartDay()
    {
        $this->assertEquals(
            DayInterface::MONDAY,
            $this->helper->getStartDay(),
            'Start day was not initialised to Monday'
        );

        $days = array(
            DayInterface::MONDAY,
            DayInterface::TUESDAY,
            DayInterface::WEDNESDAY,
            DayInterface::THURSDAY,
            DayInterface::FRIDAY,
            DayInterface::SATURDAY,
            DayInterface::SUNDAY,
        );

        foreach ($days as $day) {
            $result = $this->helper->setStartDay($day);

            $this->assertSame($this->helper, $result, 'setStartDay() did not return $this');

            $this->assertEquals(
                $day,
                $this->helper->getStartDay(),
                'getStartDay() returnered incorrect value for ' . $day . '.'
            );
        }
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
        $startDay = 5;

        $calendar = $this->getMock('BmCalendar\Calendar');
        $params = array(
            'calendar' => $calendar,
            'startDay' => $startDay,
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
        $this->helper->setStartDay($startDay);

        $result = $this->helper->showMonth($year, $month);

        $this->assertEquals($output, $result, 'The output didn\'t match.');
    }

    /**
     * Test the internal rendering of the calendar.
     *
     * @covers BmCalendar\View\Helper\Calendar::showMonth
     * @covers BmCalendar\View\Helper\Calendar::setRenderer
     * @covers BmCalendar\View\Helper\Calendar::getRenderer
     * @depends testSetCalendar
     *
     * @return void
     */
    public function testShowWithRenderer()
    {
        $year     = 2013;
        $month    = 7;
        $result   = 'Calendar';
        $startDay = 3;

        $calendar = $this->getMock('BmCalendar\Calendar');

        $renderer = $this->getMock('BmCalendar\Renderer\CalendarRendererInterface');

        $renderer->expects($this->once())
                 ->method('setCalendar')
                 ->with($this->equalTo($calendar));

        $renderer->expects($this->once())
                 ->method('setStartDay')
                 ->with($this->equalTo($startDay));

        $renderer->expects($this->once())
                 ->method('renderMonth')
                 ->with($this->equalTo($year), $this->equalTo($month))
                 ->will($this->returnValue($result));

        $this->helper->setStartDay($startDay);

        $this->helper->__invoke($calendar);

        $this->helper->setRenderer($renderer);

        $this->assertEquals(
            $result,
            $this->helper->showMonth($year, $month)
        );
    }
}
