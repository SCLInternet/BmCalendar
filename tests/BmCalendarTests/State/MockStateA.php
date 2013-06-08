<?php

namespace BmCalendarTests\State;

use BmCalendar\DayStateInterface;

class MockStateA implements DayStateInterface
{
    public static function type()
    {
        return 'mock-a';
    }
}
