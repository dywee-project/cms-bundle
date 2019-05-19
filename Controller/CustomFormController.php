<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\CustomForm;
use Dywee\CMSBundle\Entity\FormResponseContainer;
use Dywee\CoreBundle\Controller\ParentController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CustomFormController extends ParentController
{
    protected $tableViewName = 'custom_form_table';

    /**
     * @Route(name="custom_form_table", path="admin/cms/customForm")
     */
    public function myTableAction()
    {
        return parent::tableAction();
    }

    /**
     * @Route(name="custom_form_add", path="admin/cms/customForm/add")
     */
    public function myAddAction(Request $request)
    {
        return parent::addAction($request);
    }

    /**
     * @Route(name="custom_form_update", path="admin/cms/customForm/{id}/update")
     */
    public function myUpdateAction(CustomForm $customForm, Request $request)
    {
        return parent::updateAction($customForm, $request);
    }

    /**
     * @Route(name="custom_form_delete", path="admin/cms/customForm/{id}/delete")
     */
    public function myDeleteAction(CustomForm $customForm, Request $request)
    {
        return parent::deleteAction($customForm, $request);
    }

    /**
     * @param $customForm
     *
     * @return \Symfony\Component\Form\FormBuilder
     *
     * @deprecated call the service directely
     */
    public function buildForm(CustomForm $customForm)
    {
        return $this->get('dywee_cms.custom_form_builder')->buildForm($customForm);
    }

    /**
     * @Route(name="custom_form_preview", path="admin/cms/customForm/{id}/preview")
     */
    public function previewAction(CustomForm $customForm)
    {
        $form = $this->buildForm($customForm);

        return $this->render(
            'DyweeCMSBundle:CustomForm:preview.html.twig',
            ['customForm' => $customForm, 'form' => $form->createView()]
        );
    }

    /**
     * @Route(name="custom_form_render", path="cms/customForm/{id}")
     */
    public function renderAction(CustomForm $customForm, Request $request)
    {
        $form = $this->buildForm($customForm);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $response = new FormResponseContainer();
            $response->setFromForm($customForm, $form->getData());

            /*$notification = new Notification();
            $notification->setContent('Une nouvelle réponse a été validée pour le formulaire');
            $notification->setBundle('module');
            $notification->setType('form.response.new');
            $notification->setRoutingPath('custom_formResponse_view');*/

            $em = $this->getDoctrine()->getManager();

            $em->persist($response);

            /*$notification->setRoutingArguments(json_encode(array('id' => $response->getId())));

            $em->persist($notification);*/
            $em->flush();

            return $this->render('DyweeCMSBundle:Render:validated_form.html.twig');
        }

        return $this->render(
            'DyweeCMSBundle:Render:render_form.html.twig',
            ['customForm' => $customForm, 'form' => $form->createView()]
        );
    }

    /**
     * @Route(name="custom_form_json", path="admin/cms/customForm/json")
     * TODO expose true
     */
    public function jsonAction()
    {
        $customFormRepository = $this->getDoctrine()->getRepository('DyweeCMSBundle:CustomForm');

        $customFormList = $customFormRepository->findAll();

        if (count($customFormList) > 0) {
            $serializer = $this->get('serializer');
            $response = ['type' => 'success', 'data' => $serializer->normalize($customFormList)];
        } else {
            $response = ['type' => 'empty'];
        }

        return $this->json($response);
    }

    /*public function pageAction(Page $page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fr = $em->getRepository('DyweeCMSBundle:CustomForm');

        $customForm = $fr->findOneById($page->getChildArguments());

        $fb = $this->buildForm($customForm);
        $fb->add('Envoyer', 'submit');
        $form = $fb->getForm();

        if($form->handleRequest($request)->isValid())
        {
            $response = new FormResponseContainer();
            $response->setFromForm($customForm, $form->getData());

            $notification = new Notification();
            $notification->setContent('Une nouvelle réponse a été validée pour un formulaire');
            $notification->setBundle('module');
            $notification->setType('form.response.new');

            $em->persist($response);
            $em->flush();

            $notification->setRoutingArguments(json_encode(array('id' => $response->getId())));

            $em->persist($notification);
            $em->flush();

        }
        return $this->render('DyweeCMSBundle:CustomForm:page.html.twig', array('page' => $page, 'customForm' => $customForm, 'form' => $form->createView()));
    }*/
}

