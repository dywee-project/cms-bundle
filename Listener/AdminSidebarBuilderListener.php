<?php

namespace Dywee\CMSBundle\Listener;

use Dywee\CMSBundle\Service\CMSAdminSidebarHandler;
use Dywee\CoreBundle\DyweeCoreEvent;
use Dywee\CoreBundle\Event\AdminSidebarBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class AdminSidebarBuilderListener implements EventSubscriberInterface{
    private $CMSAdminSidebarHandler;

    public function __construct(CMSAdminSidebarHandler $CMSAdminSidebarHandler)
    {
        $this->CMSAdminSidebarHandler = $CMSAdminSidebarHandler;
    }


    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
            DyweeCoreEvent::BUILD_ADMIN_SIDEBAR => array('addElementToSidebar', -5)
        );
    }

    public function addElementToSidebar(AdminSidebarBuilderEvent $adminSidebarBuilderEvent)
    {
        $adminSidebarBuilderEvent->addAdminElement($this->CMSAdminSidebarHandler->getSideBarMenuElement());
    }

}