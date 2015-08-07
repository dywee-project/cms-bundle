<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\Page;
use Dywee\CMSBundle\Entity\PageLang;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function navbarAction()
    {
        $pr = $this->getDoctrine()->getManager()->getRepository('DyweeCMSBundle:Page');
        /*$pageList = $pr->findBy(
            array('inMenu' => 1, 'parent' => null),
            array('menuOrder' => 'asc')
        );*/

        $pageList = $pr->getMenu();

        return $this->render('DyweeCMSBundle:CMS:menu.html.twig', array('pageList' => $pageList)
        );
    }
}
