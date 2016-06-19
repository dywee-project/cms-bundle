<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\DyweeForm;
use Dywee\CMSBundle\Entity\FormResponseContainer;
use Dywee\CMSBundle\Entity\Page;
use Dywee\CMSBundle\Form\DyweeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DyweeFormController extends Controller
{
    public function tableAction()
    {
        $dfr = $this->getDoctrine()->getManager()->getRepository('DyweeCMSBundle:DyweeForm');

        return $this->render('DyweeCMSBundle:CustomForm:table.html.twig', array('dyweeForms' => $dfr->findAll()));
    }

    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $customForm = new DyweeForm();

        $form = $this->get('form.factory')->create(DyweeFormType::class, $customForm);

        if($form->handleRequest($request)->isValid())
        {
            $em->persist($customForm);
            $em->flush();

            $request->getSession()->getFlashBag('success', 'Formulaire correctement créé');

            return $this->redirect($this->generateUrl('cms_customForm_table'));
        }

        return $this->render('DyweeCMSBundle:CustomForm:add.html.twig', array('form' => $form->createView()));
    }

    public function updateAction(DyweeForm $customForm, Request $request)
    {
        $form = $this->get('form.factory')->create(DyweeFormType::class, $customForm);

        if($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customForm);
            $em->flush();

            $request->getSession()->getFlashBag('success', 'Formulaire correctement modifié');

            return $this->redirect($this->generateUrl('cms_customForm_table'));
        }
        return $this->render('DyweeCMSBundle:CustomForm:edit.html.twig', array('customForm' => $customForm,'form' => $form->createView()));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $dfr = $em->getRepository('DyweeCMSBundle:DyweeForm');

        $df = $dfr->findOneById($id);

        if($df)
        {
            $em->remove($df);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Formulaire bien supprimé');

            return $this->redirect($this->generateUrl('cms_customForm_table'));
        }
        throw $this->createNotFoundException('Ce formulaire n\'existe plus');
    }

    public function buildForm($customForm)
    {
        $fb = $this->createFormBuilder(array());

        $index = 1;
        foreach($customForm->getFields() as $field)
        {
            $options = array();

            $type = $field->getType();
            if($type == 'select')
            {
                $type = 'choice';
            }
            else if($type == 'checkbox')
            {
                $type = 'choice';
                $options['expanded'] = true;
                $options['multiple'] = true;
            }
            else if($type == 'radio')
            {
                $type = 'choice';
                $options['expanded'] = true;
            }

            if($type == 'choice')
            {
                $options['choices'] = $field->getPossibleValuesArray();
            }


            $options['required'] = $field->isRequired();
            $options['label'] = $field->getLabel().($field->isRequired()?' *':'');
            $options['attr'] = array(
                'placeholder' => $field->getPlaceholder()
            );

            $fb->add($field->getId(), $type, $options);

            $index++;
        }

        return $fb;
    }

    public function previewAction(DyweeForm $customForm)
    {
        $fb = $this->buildForm($customForm);
        $fb->add('Envoyer', 'button');
        $form = $fb->getForm();

        return $this->render('DyweeCMSBundle:CustomForm:preview.html.twig', array('customForm' => $customForm, 'form' => $form->createView()));
    }

    /*public function pageAction(Page $page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fr = $em->getRepository('DyweeCMSBundle:DyweeForm');

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

    public function renderAction(DyweeForm $customForm, Request $request)
    {

        $fb = $this->buildForm($customForm);
        $fb->add('Envoyer', 'submit');
        $form = $fb->getForm();

        $form->handleRequest($request);

        if($form->isValid())
        {
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

    public function jsonAction()
    {
        $customFormRepository = $this->getDoctrine()->getRepository('DyweeCMSBundle:DyweeForm');

        $customFormList = $customFormRepository->findAll();

        echo 'ici'; exit;
        var_dump($customFormList); exit;

        $serializer = $this->get('serializer');

        if(count($customFormList) > 0)
            $response = array('type' => 'success', 'data' => $serializer->normalize($customFormList));
        else
            $response = array('type' => 'empty');

        return new Response(json_encode($response));
    }
}

