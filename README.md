BmCalendar
==========

[![Build Status](https://travis-ci.org/SCLInternet/BmCalendar.png?branch=master)](https://travis-ci.org/SCLInternet/BmCalendar)
[![Coverage Status](https://coveralls.io/repos/SCLInternet/BmCalendar/badge.png?branch=master)](https://coveralls.io/r/SCLInternet/BmCalendar?branch=master)

Calendar module which provide the ability to block off days.

Installation
------------

Installation can be done easily via composer by running:

`composer.php require sclinternet/bm-calendar`

When prompted for a version enter `dev-master`.

After the composer has completed simple add `BmCalendar` to the `modules`
section in your application config.

Basic Usage
-----------

Simply create a calendar like so:

```php
$calendar = new \BmCalendar\Calendar();
```

And the use the view helper to display a month in your view:

```php
echo $this->calendar($calendar)->showMonth(2013, 05);
```

The View Helper
---------------

By default the view helper will render a month in a simple HTML table. If you
wish to further customise the output you can do this by rendering using a
custom view partial.

To do this simply call the view helper like so:

```php
echo $this->calendar($calendar)->setPartial('partial-name')->showMonth(2013, 05);
```

The partial will have the following parameters passed to it:

* **$calendar** - An instance of `BmCalendar\Calendar`
* **$month** - The month to be rendered (int)
* **$year** - The year that the month to be rendered belongs to (int)


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
