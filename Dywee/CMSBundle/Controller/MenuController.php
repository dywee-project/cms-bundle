<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\Page;
use Dywee\CMSBundle\Entity\PageLang;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function navbarAction($position = 'top')
    {
        $em = $this->getDoctrine()->getManager();
        $pageRepository = $em->getRepository('DyweeCMSBundle:Page');

        /*$pageList = $pr->findBy(
            array('inMenu' => 1, 'parent' => null),
            array('menuOrder' => 'asc')
        );*/

        $websiteId = $this->container->getParameter('website.id');
        $pageList = $pageRepository->getMenu($websiteId);


        if($position == 'top')
        {
            $websiteRepository = $em->getRepository('DyweeWebsiteBundle:Website');
            $website = $websiteRepository->findOneById($websiteId);

            return $this->render('DyweeCMSBundle:CMS:menu.html.twig', array('pageList' => $pageList, 'websiteName' => $website->getPublicName())
            );
        }

        else if($position == 'footer')
            return $this->render('DyweeCMSBundle:Nav:footer.html.twig', array('pageList' => $pageList)
            );
    }
}
