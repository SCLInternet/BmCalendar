<?php

namespace BmCalendar;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

/**
 * The module class for BmCalendar.
 *
 * BmCalendar provides an simple set of calendar classes with customisable
 * states which can be applied to each day.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Module implements
    AutoloaderProviderInterface,
    ViewHelperProviderInterface
{
    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'calendar' => 'BmCalendar\View\Helper\Calendar',
            ),
        );
    }
}
