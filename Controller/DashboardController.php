<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function cardAction()
    {
        $count = $this->getDoctrine()->getRepository('DyweeCMSBundle:Page')->countPage(Page::STATE_PUBLISHED);

        return $this->render('DyweeCMSBundle:Dashboard:card.html.twig', array('count' => $count));
    }
}