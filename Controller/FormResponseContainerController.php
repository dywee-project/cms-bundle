<?php

namespace Dywee\CMSBundle\Controller;

use Dywee\CMSBundle\Entity\FormResponseContainer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormResponseContainerController extends ParentController
{
    /**
     * @Route(name="form_response_view", path="/admin/cms/customFormResponse/{id}", requirements={"id": "\d+"})
     *
     * @param FormResponseContainer $responses
     * @param Request               $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myViewAction(FormResponseContainer $responses, Request $request)
    {
        $fb = $this->createFormBuilder([]);

        $index = 1;
        foreach ($responses->getFieldResponses() as $response) {
            $field = $response->getField();
            $options = [];
            $options['data'] = $response->getValue();

            switch ($field->getType()) {
                case CheckboxType::class:
                    //$options['expanded'] = true;
                    //$options['multiple'] = true;

                case CollectionType::class:
                    //$options['expanded'] = true;
            }

            $options['required'] = $field->isRequired();
            $options['label'] = $field->getLabel() . ($field->isRequired() ? ' *' : '');
            $options['attr'] = [
                'placeholder' => $field->getPlaceholder(),
            ];

            $fb->add($field->getId(), $field->getType(), $options);

            $index++;
        }

        $form = $fb->getForm();

        $em = $this->getDoctrine()->getManager();
        $responses->setIsReaded(true);

        $em->persist($responses);
        $em->flush();

        return $this->render(
            'DyweeCMSBundle:FormResponse:view.html.twig',
            ['response' => $form->createView(), 'customForm' => $responses->getForm()]
        );
    }

    /**
     * @Route(name="form_response_table", path="/admin/form/customFormResponse/{id}/table", requirements={"id": "\d+"})
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myTableAction($id)
    {
        return parent::tableFromParentAction($id);
    }

    /**
     * @Route(name="form_response_delete", path="/admin/form/customFormResponse/{id}/delete", requirements={"id": "\d+"})
     *
     * @param int $id
     * @param Request $request
     *
     * @return bool|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function myDeleteAction($id, Request $request)
    {
        return parent::deleteAction($id, $request);
    }
}
