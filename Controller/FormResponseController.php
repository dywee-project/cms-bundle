<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\FormResponseContainer;
use Symfony\Component\HttpFoundation\Request;

class FormResponseController extends ParentController
{
    public function myViewAction(FormResponseContainer $responses, Request $request)
    {
        $fb = $this->createFormBuilder(array());

        $index = 1;
        foreach($responses->getFieldResponses() as $response)
        {
            $field = $response->getField();
            $options = array();
            $options['data'] = $response->getValue();

            $type = $field->getType();

            if($type == 'select')
            {
                $type = 'text';
            }
            else if($type == 'checkbox')
            {
                $type = 'text';
                //$options['expanded'] = true;
                //$options['multiple'] = true;
            }
            else if($type == 'radio')
            {
                $type = 'text';
                //$options['expanded'] = true;
            }

            $options['required'] = $field->isRequired();
            $options['label'] = $field->getLabel().($field->isRequired()?' *':'');
            $options['attr'] = array(
                'placeholder' => $field->getPlaceholder()
            );



            $fb->add($field->getId(), $type, $options);

            $index++;
        }

        $form = $fb->getForm();

        $em = $this->getDoctrine()->getManager();
        $responses->setIsReaded(true);

        $em->persist($responses);
        $em->flush();

        return $this->render('DyweeCMSBundle:FormResponse:view.html.twig', array('response' => $form->createView(), 'customForm' => $responses->getForm()));
    }
}
