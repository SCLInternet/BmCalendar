<?php

namespace BmCalendarTests\State;

use BmCalendar\DayStateInterface;

class MockStateB implements DayStateInterface
{
    public static function uid()
    {
        return 'mock-b';
    }
}
