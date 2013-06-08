<?php

namespace BmCalendarTests\State;

use BmCalendar\DayStateInterface;

class MockStateB implements DayStateInterface
{
    public static function type()
    {
        return 'mock-b';
    }
}
