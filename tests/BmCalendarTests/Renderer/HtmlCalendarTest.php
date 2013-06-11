<?php
/**
  * BmCalendar Module (https://github.com/SCLInternet/BmCalendar)
  *
  * @link https://github.com/SCLInternet/BmCalendar for the canonical source repository
  * @license http://opensource.org/licenses/MIT The MIT License (MIT)
  */

namespace BmCalendarTests\Renderer;

use BmCalendar\Renderer\HtmlCalendar;
use BmCalendar\Day;
use BmCalendar\Month;
use BmCalendar\Year;
use BmCalendarTests\State\MockStateA;

/**
 * Unit tests for {@see HtmlCalendar}
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class HtmlCalendarTests extends \PHPUnit_Framework_TestCase
{
    /**
     * The instance to be tested.
     *
     * @var HtmlCalendar
     */
    protected $renderer;

    /**
     * Prepare the instance to be tested
     *
     * @return void
     */
    protected function setUp()
    {
        $this->renderer = new HtmlCalendar;
    }

    /**
     * Test the output of monthTitle.
     *
     * @covers BmCalendar\Renderer\HtmlCalendar::monthTitle
     *
     * @return void
     */
    public function testMonthTitle()
    {
        $month = new Month(new Year(2013), 3);

        $expected  = '<thead>';
        $expected .= '<tr>';
        $expected .= '<th colspan="7" class="bm-calendar-month-title">March</th>';
        $expected .= '</tr><tr>';
        $expected .= '<th>Mon</th>';
        $expected .= '<th>Tue</th>';
        $expected .= '<th>Wed</th>';
        $expected .= '<th>Thu</th>';
        $expected .= '<th>Fri</th>';
        $expected .= '<th class="bm-calendar-weekend">Sat</th>';
        $expected .= '<th class="bm-calendar-weekend">Sun</th>';
        $expected .= '</tr>';
        $expected .= '</thead>';

        $this->assertEquals($expected, $this->renderer->monthTitle($month));
    }

    /**
     * Test the output of renderDay with a week day with no state or action.
     *
     * @covers BmCalendar\Renderer\HtmlCalendar::renderDay
     * @dataProvider weekdayProvider
     *
     * @return void
     */
    public function testRenderDayWithWeekday($dom, $dayName)
    {
        // 7th March 2013 was a Thursday
        $day = new Day(new Month(new Year(2013), 3), $dom);

        $expected = '<td>' . $dom . '</td>';

        $this->assertEquals(
            $expected,
            $this->renderer->renderDay($day),
            'Error on week day ' . $dayName .  ' with no states or action'
        );
    }

    /**
     * Test the output of renderDay with a weekend day with no state or action.
     *
     * @covers BmCalendar\Renderer\HtmlCalendar::renderDay
     * @dataProvider weekendProvider
     * 
     * @return void
     */
    public function testRenderDayWithWeekendDay($dom, $dayName)
    {
        // 9th March 2013 was a Saturday
        $day = new Day(new Month(new Year(2013), 3), $dom);

        $expected = '<td class="bm-calendar-weekend">' . $dom . '</td>';

        $this->assertEquals(
            $expected,
            $this->renderer->renderDay($day),
            'Error on ' . $dayName . ' day with no states or action'
        );
    }

    public function weekendProvider()
    {
        return array(
            array(9, 'Saturday'),
            array(10, 'Sunday')
        );
    }

    public function weekdayProvider()
    {
        return array(
            array(11,'Monday'),
            array(12,'Tuesday'),
            array(13,'Wednesday'),
            array(14,'Thursday'),
            array(15,'Friday'),
        );
    }

    /**
     * Test the output of renderDay with a week day with an action.
     *
     * @covers BmCalendar\Renderer\HtmlCalendar::renderDay
     *
     * @return void
     */
    public function testRenderDayWithAction()
    {
        // Test with an action
        $day = new Day(new Month(new Year(2013), 3), 7);

        $actionUrl = 'http://dosome.action/index?param1=value&param2=value';
        $day->setAction($actionUrl);

        $expected = '<td><a href="'. htmlentities($actionUrl) . '">7</a></td>';

        $this->assertEquals(
            $expected,
            $this->renderer->renderDay($day),
            'Error on week day with no states or an action'
        );
    }

    /**
     * Test the output of renderDay with a week day with a state.
     *
     * @covers BmCalendar\Renderer\HtmlCalendar::renderDay
     *
     * @return void
     */
    public function testRenderDayWithAState()
    {
        // Test with a state
        $day = new Day(new Month(new Year(2013), 3), 7);

        $expected = '<td class="bm-calendar-state-' . MockStateA::type() . '">7</td>';

        $state = new MockStateA();

        $day->addState($state);

        $this->assertEquals(
            $expected,
            $this->renderer->renderDay($day),
            'Error on week day with states and no action'
        );
    }
}
