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

By default the view helper will render a month in a simple HTML table created by 
`BmCalendar\Renderer\HtmlCalendar`.

Changing the start day
======================

By default the calendar will render with Monday days in the first column, if 
you wish to change this you can invoke the view helper like so.

```php
echo $this->calendar($calendar)
          ->setStartDay(\BmCalendar\DayInterface::WEDNESDAY)
          ->showMonth(2013, 5);
```

Renderer Classes
================

You can also create your own renderer classes by implementing
`BmCalendar\Renderer\RendererInterface` then tell the view helper to use your
renderer instead like so:

```php
echo $this->calendar($calendar)->setRenderer(new MyRenderer())->showMonth(2013, 5);
```

Partials
========

Alternatively you can customise the rendering process by using partial templates.


To do this simply call the view helper like so:

```php
echo $this->calendar($calendar)->setPartial('partial-name')->showMonth(2013, 5);
```

The partial will have the following parameters passed to it:

* **$startDay** - The day of the week to start the calendar on (int 1-7)
* **$calendar** - An instance of `BmCalendar\Calendar`
* **$month** - The month to be rendered (int)
* **$year** - The year that the month to be rendered belongs to (int)

Day Providers
-------------

Day providers are used to add extra states to a day.

States must implement `\BmCalendar\State\DayStateInterface`.

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

Day States
----------
Day states allow you to add values to days in the calendar (e.g. if there is
an event on on that day).


### Creating a day state class
A day state is a class which implements `BmCalendar\State\DayStateInterface` which
simply provides a static method called `type()` to identify the state by.

There is also a `BmCalendar\State\AbstractDayState` class available which can be
directly extended which will implement the `type()` method for you.

You can quickly define a state like so:

```php
use BmCalendar\State\AbstractDayState;

class HasEventState extends AbstractDayState
{
}
```

### Using day states

To add a state to a `Day` object you simply call the `addState($state)` method:

```php
$day->addState(new HasEventState())
```

To get a list of all states attached to a `Day` object you can call

```php
$day->getStates();
```

An if you want to check for a specific type of state for a given day you can 
use the static `type()` method like so:

```php
$state = $day->getState(HasEventState::type());
```

If the `Day` doesn't contain a state of the requested type `NULL` is returned.
