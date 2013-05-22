BmCalendar
==========

Calendar module which provide the ability to block off days.

Basic Usage
-----------

Simply create a calendar like so:

```php
$calendar = new \BmCalendar\Calendar();
```

An the use the view helper to display a month in your view:

**view helper not implemented yet**

```php
echo $this->calendar($calendar)->month(2013, 05);
```

Day Providers
-------------

Day providers are used to add extra states to a day.

States must implement `\BmCalendar\DayStateInterface`.

A day provider can be implemented like so:

```php
use BmCalendar\DayProviderInterface;
use BmCalendar\Day;
use BmCalendar\Month;

class MyDayProvider implements DayProviderInterface
{
    public $database;

    public function createDay(Month $month, $dayNo)
    {
        $day = new Day($month, $dayNo);

        $avaliable = $this->database->checkAvailability(
            $month->getYear()->value(),
            $month->value(),
            $dayNo
        );

        if (!$available) {
            $day->addState(new BookedState());

            return $day;
        }

        $day->addState(new AvailableState());
        $day->setAction('http://url-to-booking-form');

        return $day;
    }
}
```

To use your day provider simply pass it to the Calendar constructor like so:

```php
$provider = new MyDayProvider();
$provider->database = new AvailabilityDatabasesChecker();

$calendar = new \BmCalendar\Calendar($provider);
```
