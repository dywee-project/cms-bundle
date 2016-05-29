<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function installAction()
    {
        $homepage = new Page();
        $homepage->setType(PAGE::TYPE_HOMEPAGE);
        $homepage->setActive(true);
        $homepage->setInMenu(true);
        $homepage->setMenuName('Accueil');
        $homepage->setName('Accueil');
        $homepage->setSeoUrl('accueil');
        $homepage->setState(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($homepage);
        $em->flush();

        return $this->redirectToRoute('admin_dashboard');
    }
}
