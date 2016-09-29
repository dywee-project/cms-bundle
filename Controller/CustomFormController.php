<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\CustomForm;
use Dywee\CMSBundle\Entity\FormResponseContainer;
use Dywee\CoreBundle\Controller\ParentController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CustomFormController extends ParentController
{
    /**
     * @Route(name="cms_customForm_table", path="admin/cms/customForm")
     */
    public function myTableAction()
    {
        return parent::tableAction();
    }

    /**
     * @Route(name="cms_customForm_add", path="admin/cms/customForm/add")
     */
    public function myAddAction(Request $request)
    {
        return parent::addAction($request);
    }

    /**
     * @Route(name="cms_customFormupdate", path="admin/cms/customForm/{id}/update")
     */
    public function myUpdateAction(CustomForm $customForm, Request $request)
    {
        return parent::updateAction($customForm, $request);
    }

    /**
     * @Route(name="cms_customForm_delete", path="admin/cms/customForm/{id}/delete")
     */
    public function myDeleteAction(CustomForm $customForm)
    {
        return parent::deleteAction($customForm);
    }

    public function buildForm($customForm)
    {
        $fb = $this->createFormBuilder(array());

        $index = 1;
        foreach ($customForm->getFields() as $field) {
            $options = array();

            $type = $field->getType();

            switch ($type) {
                case 'select':
                    $type = 'choice';
                    break;

                case 'checkbox':
                    $type = 'choice';
                    $options['expanded'] = true;
                    $options['multiple'] = true;
                    break;

                case 'radio':
                    $type = 'choice';
                    $options['expanded'] = true;
                    break;
            }

            if ($type === 'choice') {
                $options['choices'] = $field->getPossibleValuesArray();
            }

            $options['required'] = $field->isRequired();
            $options['label'] = $field->getLabel() . ($field->isRequired() ? ' *' : '');
            $options['attr'] = array(
                'placeholder' => $field->getPlaceholder()
            );

            $fb->add($field->getId(), $type, $options);

            $index++;
        }

        return $fb;
    }

    /**
     * @Route(name="cms_customForm_preview", path="admin/cms/customForm/{id}/preview")
     */
    public function previewAction(CustomForm $customForm)
    {
        $fb = $this->buildForm($customForm);
        $fb->add('Envoyer', 'button');
        $form = $fb->getForm();

        return $this->render('DyweeCMSBundle:CustomForm:preview.html.twig', array('customForm' => $customForm, 'form' => $form->createView()));
    }

    /**
     * @Route(name="cms_customForm_display", path="cms/customForm/{id}")
     */
    public function renderAction(CustomForm $customForm, Request $request)
    {

        $fb = $this->buildForm($customForm);
        $fb->add('Envoyer', 'submit');
        $form = $fb->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $response = new FormResponseContainer();
            $response->setFromForm($customForm, $form->getData());

            /*$notification = new Notification();
            $notification->setContent('Une nouvelle réponse a été validée pour le formulaire');
            $notification->setBundle('module');
            $notification->setType('form.response.new');
            $notification->setRoutingPath('cms_customFormResponse_view');*/

            $em = $this->getDoctrine()->getManager();

            $em->persist($response);

            /*$notification->setRoutingArguments(json_encode(array('id' => $response->getId())));

            $em->persist($notification);*/
            $em->flush();

            return $this->render('DyweeCMSBundle:Render:validated_form.html.twig');
        }
        return $this->render('DyweeCMSBundle:Render:render_form.html.twig', array('customForm' => $customForm, 'form' => $form->createView()));
    }

    /**
     * @Route(name="cms_customForm_json", path="admin/cms/customForm/json")
     * TODO expose true
     */
    public function jsonAction()
    {
        $customFormRepository = $this->getDoctrine()->getRepository('DyweeCMSBundle:CustomForm');

        $customFormList = $customFormRepository->findAll();

        if (count($customFormList) > 0) {
            $serializer = $this->get('serializer');
            $response = array('type' => 'success', 'data' => $serializer->normalize($customFormList));
        } else {
            $response = array('type' => 'empty');
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

