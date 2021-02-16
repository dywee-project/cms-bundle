<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\DyweeCMSEvent;
use Dywee\CMSBundle\Event\FooterBuilderEvent;
use Dywee\CMSBundle\Event\NavbarBuilderEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends AbstractController
{
    public function navbarAction($position = 'top')
    {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository('DyweeCMSBundle:Page');

        $pageList = $pageRepository->findAll();

        if ($position === 'top') {
            $event = new NavbarBuilderEvent(['pageList' => $pageList]);

            $this->get('event_dispatcher')->dispatch($event, DyweeCMSEvent::BUILD_NAVBAR);

            return $this->render('DyweeCMSBundle:Nav:menu.html.twig', $event->getData());
        }

        if ($position === 'footer') {
            $event = new FooterBuilderEvent(['pageList' => $pageList]);

            $this->get('event_dispatcher')->dispatch($event, DyweeCMSEvent::BUILD_NAVBAR);

            return $this->render('DyweeCMSBundle:Nav:footer.html.twig', $event->getData());
        }
    }
}
