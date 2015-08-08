<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\Page;
use Dywee\CMSBundle\Entity\PageStat;
use Dywee\CMSBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    public function indexAction()
    {
        $pr = $this->getDoctrine()->getManager()->getRepository('DyweeCMSBundle:Page');
        $page = $pr->findHomePage();

        if($page == -1)
            return $this->redirect($this->generateUrl('dywee_install'));

        return $this->viewPageAction($page);
    }

    public function viewPageAction($page)
    {
        $type = $page['type'];

        switch($type)
        {
            case 1: return $this->renderHomeAction($page);
            case 3: return $this->redirect($this->generateUrl('dywee_message_new'));
            case 4: return $this->render('DyweeNewsBundle:CMS:page.html.twig', array('page' => $page));
            case 5: return $this->render('DyweeCMSBundle:CMS:view.html.twig', array('page' => $page));
            case 6: return $this->forward('DyweeEshopBundle:Eshop:pageHandler', array('page' => $page));
            case 7: return $this->render('DyweeBlogBundle:Blog:page.html.twig', array('page' => $page));
            case 9: return $this->forward('DyweeFaqBundle:Faq:page', array('page' => $page));
            default: return $this->render('DyweeCMSBundle:CMS:page.html.twig', array('page' => $page));
        }
    }

    public function inMenuSwitchAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();

        $page->setInMenu(!$page->getInMenu());
        $em->persist($page);
        $em->flush();

        return $this->redirect($this->generateUrl('dywee_cms_table'));
    }

    public function renderHomeAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $pr = $em->getRepository('DyweeCMSBundle:Page');
        $nr = $em->getRepository('DyweeNewsBundle:News');
        $pror = $em->getRepository('DyweeProductBundle:Product');

        $pageStat = new PageStat();
        $pageStat->setPage($pr->findOneById($page['id']));

        $em->persist($pageStat);
        $em->flush();

        $data = array('page' => $page);

        return $this->render('DyweeCMSBundle:CMS:view.html.twig', $data);
    }

    public function adminViewAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();

        $psr = $em->getRepository('DyweeCMSBundle:PageStat');
        $pss = $psr->findLastStatsForPage($page);
        return $this->render('DyweeCMSBundle:Admin:details.html.twig', array('page' => $page, 'stats' => $pss));
    }

    public function tableAction()
    {
        $pr = $this->getDoctrine()->getManager()->getRepository('DyweeCMSBundle:Page');
        $ps = $pr->findAll();
        return $this->render('DyweeCMSBundle:CMS:table.html.twig', array('pageList' => $ps));
    }

    public function viewAction($data)
    {
        $em = $this->getDoctrine()->getManager();
        $pr = $em->getRepository('DyweeCMSBundle:Page');
        if(is_numeric($data))
            $page = $pr->findById($data);
        else $page = $pr->findBySeoUrl($data);

        if($page)
        {
            $pageStat = new PageStat();
            $pageStat->setPage($pr->findOneById($page['id']));

            $em->persist($pageStat);
            $em->flush();

            return $this->viewPageAction($page);
        }
        throw $this->createNotFoundException('Page introuvable');
    }

    public function viewContentAction($data)
    {
        $em = $this->getDoctrine()->getManager();
        $pr = $em->getRepository('DyweeCMSBundle:Page');
        $param = is_numeric($data)?array('id' => $data):0;
        $page = $pr->findOneBy($param);

        $pageStat = new PageStat();
        $pageStat->setPage($page);

        $em->persist($pageStat);
        $em->flush();

        return $this->render('DyweeCMSBundle:CMS:viewContent.html.twig', array('page' => $page));
    }

    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $page = new Page();
        $page->setIdSite(1);

        $form = $this->get('form.factory')->create(new PageType(), $page);

        if($form->handleRequest($request)->isValid())
        {
            $page->setUpdatedBy($this->get('security.token_storage')->getToken()->getUser());
            $em->persist($page);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Page bien créée');

            return $this->redirect($this->generateUrl('dywee_cms_table'));
        }
        return $this->render('DyweeCMSBundle:CMS:add.html.twig', array('form' => $form->createView()));
    }

    public function updateAction(Page $page, Request $request)
    {
        $form = $this->get('form.factory')->create(new PageType(), $page);

        if($form->handleRequest($request)->isValid())
        {
            $page->setUpdatedBy($this->get('security.token_storage')->getToken()->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Page bien modifiée');

            return $this->redirect($this->generateUrl('dywee_cms_table'));
        }
        return $this->render('DyweeCMSBundle:CMS:edit.html.twig', array('page' => $page, 'form' => $form->createView()));
    }

    public function deleteAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Page bien supprimée');

        return $this->redirect($this->generateUrl('dywee_cms_table'));
    }
}
