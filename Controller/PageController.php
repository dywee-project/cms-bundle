<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\DyweeCMSEvent;
use Dywee\CMSBundle\Entity\CustomForm;
use Dywee\CMSBundle\Entity\FormResponseContainer;
use Dywee\CMSBundle\Entity\Page;
use Dywee\CMSBundle\Entity\PageStat;
use Dywee\CMSBundle\Event\HomepageBuilderEvent;
use Dywee\CMSBundle\Event\PageBuilderEvent;
use Dywee\CMSBundle\Event\PageElementModalBuilderEvent;
use Dywee\CMSBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PageController extends AbstractController
{
    /**
     * @Route(name="cms_homepage", path="/")
     */
    public function indexAction(Request $request)
    {
        $pr = $this->getDoctrine()->getManager()->getRepository('DyweeCMSBundle:Page');
        $page = $pr->findHomePage();

        if ($page == null) {
            return $this->redirect($this->generateUrl('page_install'));
        }

        return $this->viewAction($page->getId(), $request);
    }

    /**
     * @Route(name="page_inMenu_switch", path="admin/page/{id}/inMenuSwitch", requirements={"id": "\d+"})
     *
     * @param Page $page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function inMenuSwitchAction(Page $page)
    {
        $em = $this->getDoctrine()->getManager();

        $page->setInMenu(!$page->getInMenu());
        $em->persist($page);
        $em->flush();

        return $this->redirect($this->generateUrl('page_table'));
    }

    /**
     * @param $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderHomeAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $pageStat = new PageStat();
        $pageStat->setPage($page);

        $em->persist($pageStat);
        $em->flush();

        $data = ['page' => $page];

        return $this->render('DyweeCMSBundle:Page:view.html.twig', $data);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(name="page_table", path="admin/page/table")
     */
    public function tableAction()
    {
        $pr = $this->getDoctrine()->getManager()->getRepository('DyweeCMSBundle:Page');
        $ps = $pr->findAll();

        return $this->render('DyweeCMSBundle:Page:table.html.twig', ['pageList' => $ps]);
    }

    /**
     * @param         $data
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route(name="page_view", path="page/{data}")
     */
    public function viewAction($data, Request $request)
    {
        /*
         * Le problème a étudier est celui des pages comprenant un ou plusieurs formulaire(s)
         * Un render depuis une vue twig rend correctement le formulaire mais ne permet pas de le valider
         * Le formulaire soumis est considéré comme vide
         * TODO surement parce que c'est une subrequest...
         *
         * Sur les forums on ne trouve pas grand chose vu que c'est pas un use case habituel
         *
         * Piste:
         * Possible que dans l'ancien système et tous les forward etc... de controlleur, on en passait pas les Request à tous
         * et que ça foutait le bordel parce qu'il recevait un mauvais Request?
         *
         * Pourquoi ne pas traiter les formulaires en ajax?
         */

        $pageRepository = $this->getDoctrine()->getManager()->getRepository('DyweeCMSBundle:Page');

        if (is_numeric($data)) {
            $page = $pageRepository->find($data);
        } else {
            $page = $pageRepository->findOneBySeoUrl($data);
        }

        /*switch ($page->getType()) {
            case 3:
                return $this->redirect($this->generateUrl('message_new'));
            /*case 5: return $this->forward('DyweeModuleBundle:Event:page', array('page' => $page));
            case 6: return $this->forward('DyweeEshopBundle:Eshop:pageHandler', array('page' => $page));
            case 7: return $this->render('DyweeBlogBundle:Blog:page.html.twig', array('page' => $page));
            case 8: return $this->forward('DyweeModuleBundle:Form:page', array('page' => $page));
            case 9: return $this->forward('DyweeFaqBundle:Faq:page', array('page' => $page));
            case 10: return $this->forward('DyweeFaqBundle:PictureGallery:page', array('page' => $page));
            case 11: return $this->forward('DyweeFaqBundle:VideoGallery:page', array('page' => $page));
            case 12: return $this->forward('DyweeModuleBundle:MusicGallery:page', array('page' => $page));*/
        //}

        $data = ['page' => $page];

        if ($page->getType() === Page::TYPE_HOMEPAGE) {
            $event = new HomepageBuilderEvent($data);

            $this->get('event_dispatcher')->dispatch($event, DyweeCMSEvent::BUILD_HOMEPAGE);
        } else {
            $event = new PageBuilderEvent($data);

            $this->get('event_dispatcher')->dispatch($event, DyweeCMSEvent::BUILD_PAGE);
        }

        $data = array_merge($data, $event->getData());

        if ($page->hasForm()) {
            $formsId = $page->getForms();

            $em = $this->getDoctrine()->getManager();
            $formRepository = $em->getRepository(CustomForm::class);

            $formBuilderService = $this->get('dywee_cms.custom_form_builder');

            $forms = [];
            foreach ($formsId as $formId) {
                $customForm = $formRepository->find((int)$formId);

                if ($customForm) {
                    //Le form n'est pas un form à proprement parler mais un objet de type CustomForm
                    $form = $formBuilderService->buildForm($customForm);

                    //Traitement des formulaires
                    if ($form->handleRequest($request)->isValid()) {
                        $response = new FormResponseContainer();
                        $response->setFromForm($customForm, $form->getData());

                        /*
                        $notification = new Notification();
                        $notification->setContent('Une nouvelle réponse a été validée pour le formulaire');
                        $notification->setBundle('cms');
                        $notification->setType('form.response.new');
                        $notification->setRoutingPath('customFormResponse_view');
                        $notification->setWebsite(null);
                        */

                        $em->persist($response);

                        //$notification->setRoutingArguments(json_encode(['id' => $response->getId()]));

                        //$em->persist($notification);
                        $em->flush();
                    }

                    //Rendu des formulaires
                    $forms['form_' . $formId] = $form->createView();
                }
            }

            //On passe les formulaires à la vue
            $data['forms'] = $forms;
        }

        return $this->render('DyweeCMSBundle:Page:view.html.twig', $data);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route(name="page_add", path="admin/page/add")
     */
    public function addAction(Request $request)
    {
        $page = new Page();

        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->create(PageType::class, $page);

        if ($form->handleRequest($request)->isValid()) {
            $page->setUpdatedBy($this->getUser());

            $em->persist($page);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Page bien créée');

            return $this->redirect($this->generateUrl('page_table'));
        }
        $event = new PageElementModalBuilderEvent(['page' => $page, 'form' => $form->createView(), 'plugins' => []]);

        $eventToDispatch = $page->getType(
        ) == Page::TYPE_HOMEPAGE ? DyweeCMSEvent::BUILD_HOMEPAGE_ADMIN_PLUGIN_BOX : DyweeCMSEvent::BUILD_ADMIN_PLUGIN_BOX;

        $this->get('event_dispatcher')->dispatch($event, $eventToDispatch);

        return $this->render('DyweeCMSBundle:Page:add.html.twig', $event->getJSData());
    }

    /**
     * @param Page    $page
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route(name="page_update", path="admin/page/{id}/update")
     */
    public function updateAction(Page $page, Request $request)
    {
        $form = $this->get('form.factory')->create(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page->setUpdatedBy($this->get('security.token_storage')->getToken()->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Page bien modifiée');

            return $this->redirect($this->generateUrl('page_table'));
        }

        $event = new PageElementModalBuilderEvent(['page' => $page, 'form' => $form->createView(), 'plugins' => []]);

        $eventToDispatch = $page->getType(
        ) === Page::TYPE_HOMEPAGE ? DyweeCMSEvent::BUILD_HOMEPAGE_ADMIN_PLUGIN_BOX : DyweeCMSEvent::BUILD_ADMIN_PLUGIN_BOX;

        $this->get('event_dispatcher')->dispatch($event, $eventToDispatch);

        return $this->render('DyweeCMSBundle:Page:edit.html.twig', $event->getJSData());
    }

    /**
     * @param Page      $page
     * @param Page|null $pageToPromote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route(name="page_delete", path="admin/page/{id}/delete")
     * TODO route pour delete homepage a définir
     */
    public function deleteAction(Page $page, Page $pageToPromote = null)
    {
        $em = $this->getDoctrine()->getManager();
        if ($page->getType() == Page::TYPE_HOMEPAGE) {
            if (!$pageToPromote) {
                throw new Exception('Impossible de supprimer la page d\'accueil sans promouvoir une autre page');
            } else {
                $pageToPromote->setType(Page::TYPE_HOMEPAGE);
                $em->persist($page);
            }
        }
        $em->remove($page);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Page bien supprimée');

        return $this->redirect($this->generateUrl('page_table'));
    }
}
