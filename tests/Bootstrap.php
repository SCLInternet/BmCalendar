<?php
/**
  * BmCalendar Module (https://github.com/SCLInternet/BmCalendar)
  *
  * @link https://github.com/SCLInternet/BmCalendar for the canonical source repository
  * @license http://opensource.org/licenses/MIT The MIT License (MIT)
  */

$files = array(
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../vendor/autoload.php',
);

foreach ($files as $file) {
    if (file_exists($file)) {
        $loader = require __DIR__ . '/../vendor/autoload.php';

        break;
    }
}

if (!$loader) {
    throw new \RuntimeError('vendor/autoload.php not found. Have you run composer?');
}

$loader->add('BmCalendarTests\\', __DIR__);

unset($files, $file, $loader);
