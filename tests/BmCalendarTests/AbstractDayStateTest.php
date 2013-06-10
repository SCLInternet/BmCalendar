<?php

namespace BmCalendarTests;

use BmCalendarTests\State\MockStateA;

/**
 * Unit tests for {@see BmCalendar\State\AbstractDayState}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class AbstractDayStateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Make sure type returns the FQCN of the extending class.
     *
     * @covers BmCalendar\State\AbstractDayState::type
     *
     * @return void
     */
    public function testType()
    {
        $this->assertEquals(
            MockStateA::type(),
            'BmCalendarTests\State\MockStateA'
        );
    }
}
