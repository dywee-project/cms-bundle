<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\Page;
use Dywee\CMSBundle\Entity\PageElement;
use Dywee\CMSBundle\Entity\PageStat;
use Dywee\CMSBundle\Entity\PageTextElement;
use Dywee\CMSBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageAdminController extends Controller
{
    public function dashboardTableAction()
    {
        $pr = $this->getDoctrine()->getManager()->getRepository('DyweeCMSBundle:Page');
        $ps = $pr->findBy(array('lvl' => 0));
        return $this->render('DyweeCMSBundle:Admin:table.html.twig', array('pageList' => $ps));
    }

    public function viewAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();


        $pageStatRepository = $em->getRepository('DyweeCMSBundle:PageStat');

        $vues = $pageStatRepository->findLastStatsForPage($page);

        $date = new \DateTime("previous week");
        $date->modify('-1 day');

        for($i = 0; $i <= 7; $i++)
        {
            $key = $date->modify('+1 day');
            $stats[$key->format('Y-m-d')] = array('createdAt' => $key->format('d M'), 'vues' => 0);
        }

        foreach($vues as $vue)
            $stats[$vue['createdAt']]['vues'] = $vue['vues'];

        return $this->render('DyweeCMSBundle:Admin:view.html.twig', array('page' => $page, 'stats' => $stats));
    }
}
