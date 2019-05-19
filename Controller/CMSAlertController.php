<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\CMSAlert;
use Dywee\CMSBundle\Form\CMSAlertType as AlertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CMSAlertController extends Controller
{
    /**
     * @Route(name="cms_alert_table", path="cms/alert")
     */
    public function tableAction()
    {
        $alertRepository = $this->getDoctrine()->getRepository('DyweeCMSBundle:CMSAlert');
        $alerts = $alertRepository->findAll();

        return $this->render('DyweeCMSBundle:CMSAlert:table.html.twig', array('alerts' => $alerts));
    }

    /**
     * @Route(name="cms_alert_add", path="cms/alert/add")
     */
    public function addAction(Request $request)
    {
        $alert = new CMSAlert();
        $form = $this->createForm(AlertType::class, $alert);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($alert);
            $em->flush();

            return $this->redirectToRoute('cms_alert_table');
        }
        return $this->render('DyweeCMSBundle:CMSAlert:add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route(name="cms_alert_update", path="cms/alert/{id}/edit")
     */
    public function updateAction(CMSAlert $alert, Request $request)
    {
        $form = $this->createForm(AlertType::class, $alert);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($alert);
            $em->flush();

            return $this->redirectToRoute('cms_alert_table');
        }
        return $this->render('DyweeCMSBundle:CMSAlert:edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route(name="cms_alert_delete", path="cms/alert/{id}/delete")
     */
    public function deleteAction(CMSAlert $alert)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($alert);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Alerte bien supprimée');

        return $this->redirectToRoute('cms_alert_table');
    }

    /**
     * @Route(name="cms_alert_view", path="cms/alert/{id}")
     */
    public function displayAction()
    {
        $alertRepository = $this->getDoctrine()->getRepository('DyweeCMSBundle:Alert');
        $alerts = $alertRepository->findByActive(true);

        return $this->render('DyweeCMSBundle:CMSAlert:display.html.twig', array('alerts' => $alerts));
    }
}
