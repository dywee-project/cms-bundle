<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageAdminController extends AbstractController
{
    /**
     * @param Page $page
     * @return Response
     * @Route(name="admin_page_view", path="admin/page/{id}", requirements={"id": "\d+"})
     * @Route(name="page_details", path="admin/page/{id}", requirements={"id": "\d+"}) @deprecated
     */
    public function viewAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();


        $pageStatRepository = $em->getRepository('DyweeCMSBundle:PageStat');

        $vues = $pageStatRepository->findLastStatsForPage($page);

        $date = new \DateTime("previous week");
        $date->modify('-1 day');

        for ($i = 0; $i <= 7; $i++) {
            $key = $date->modify('+1 day');
            $stats[$key->format('Y-m-d')] = array('createdAt' => $key->format('d M'), 'vues' => 0);
        }

        foreach ($vues as $vue) {
            $stats[$vue['createdAt']]['vues'] = $vue['vues'];
        }

        return $this->render('DyweeCMSBundle:Admin:view.html.twig', array('page' => $page, 'stats' => $stats));
    }
}
