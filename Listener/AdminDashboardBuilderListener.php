<?php

namespace Dywee\CMSBundle\Listener;

use Dywee\CMSBundle\Service\AdminDashboardHandler;
use Dywee\CoreBundle\DyweeCoreEvent;
use Dywee\CoreBundle\Event\DashboardBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class AdminDashboardBuilderListener implements EventSubscriberInterface{
    private $adminDashboardHandler;

    public function __construct(AdminDashboardHandler $adminDashboardHandler)
    {
        $this->adminDashboardHandler = $adminDashboardHandler;
    }


    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
            DyweeCoreEvent::BUILD_ADMIN_DASHBOARD => array('addElement', -5)
        );
    }

    public function addElement(DashboardBuilderEvent $adminDashboardBuilderEvent)
    {
        $adminDashboardBuilderEvent->addElement($this->adminDashboardHandler->getElement());
    }

}