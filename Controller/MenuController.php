<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\Page;
use Dywee\CMSBundle\Entity\PageLang;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function navbarAction($position = 'top')
    {
        $pageRepository = $this->getDoctrine()->getRepository('DyweeCMSBundle:Page');

        $pageList = $pageRepository->findBy(
            array('inMenu' => true, 'parent' => null),
            array('menuOrder' => 'asc')
        );

        //$pageList = $pageRepository->findAll();


        if($position == 'top')
        {
            return $this->render(
                'DyweeCMSBundle:Nav:menu.html.twig', array('pageList' => $pageList)
            );
        }

        else if($position == 'footer')
            return $this->render(
                'DyweeCMSBundle:Nav:footer.html.twig', array('pageList' => $pageList)
            );
    }

    public function handleAction()
    {
        $pageRepository = $this->getDoctrine()->getRepository('DyweeCMSBundle:Page');

        $pageList = $pageRepository->findBy(
            array(),
            array('menuOrder' => 'asc')
        );

        return $this->render('DyweeCMSBundle:Menu:edit.html.twig', array('pages' => $pageList));
    }
}
