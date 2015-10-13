<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\Page;
use Dywee\CMSBundle\Entity\PageLang;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function navbarAction($position = 'top')
    {
        $pr = $this->getDoctrine()->getManager()->getRepository('DyweeCMSBundle:Page');
        /*$pageList = $pr->findBy(
            array('inMenu' => 1, 'parent' => null),
            array('menuOrder' => 'asc')
        );*/

        $pageList = $pr->getMenu();

        if($position == 'top')
            return $this->render('DyweeCMSBundle:CMS:menu.html.twig', array('pageList' => $pageList)
            );
        else if($position == 'footer')
            return $this->render('DyweeCMSBundle:Nav:footer.html.twig', array('pageList' => $pageList)
            );
    }
}
