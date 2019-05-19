<?php

namespace Dywee\CMSBundle\Listener;

use Dywee\CMSBundle\DyweeCMSEvent;
use Dywee\CMSBundle\Entity\Page;
use Dywee\CMSBundle\Event\PageElementModalBuilderEvent;
use Dywee\CMSBundle\Service\PageElementModalHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class PageElementModalListener implements EventSubscriberInterface
{
    private $pageElementModalHandler;

    public function __construct(PageElementModalHandler $pageElementModalHandler)
    {
        $this->pageElementModalHandler = $pageElementModalHandler;
    }


    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
            DyweeCMSEvent::BUILD_ADMIN_PLUGIN_BOX => array('addElementToPage', -5),
            DyweeCMSEvent::BUILD_HOMEPAGE_ADMIN_PLUGIN_BOX => array('addElementToHomepage', -5),
        );
    }

    public function addElementToPage(PageElementModalBuilderEvent $pageElementModalBuidlerEvent)
    {
        $pageElementModalBuidlerEvent->addData($this->pageElementModalHandler->addPageElements(), Page::TYPE_NORMALPAGE);
    }

    public function addElementToHomepage(PageElementModalBuilderEvent $pageElementModalBuidlerEvent)
    {
        $pageElementModalBuidlerEvent->addData($this->pageElementModalHandler->addPageElements(), Page::TYPE_HOMEPAGE);
    }
}
