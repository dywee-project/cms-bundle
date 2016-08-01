<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\DyweeCMSEvent;
use Dywee\CMSBundle\Entity\Page;
use Dywee\CMSBundle\Entity\PageLang;
use Dywee\CMSBundle\Event\FooterBuilderEvent;
use Dywee\CMSBundle\Event\NavbarBuilderEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function navbarAction($position = 'top')
    {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository('DyweeCMSBundle:Page');

        $pageList = $pageRepository->findAll();


        if($position == 'top')
        {
            $event = new NavbarBuilderEvent(array('pageList' => $pageList));

            $this->get('event_dispatcher')->dispatch(DyweeCMSEvent::BUILD_NAVBAR, $event);

            return $this->render('DyweeCMSBundle:Nav:menu.html.twig', $event->getData());
        }

        else if($position == 'footer'){
            $event = new FooterBuilderEvent(array('pageList' => $pageList));

            $this->get('event_dispatcher')->dispatch(DyweeCMSEvent::BUILD_NAVBAR, $event);

            return $this->render('DyweeCMSBundle:Nav:footer.html.twig', $event->getData());
        }
    }
}
