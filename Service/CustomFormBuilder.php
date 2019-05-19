<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 26/08/17
 * Time: 19:03
 */

namespace Dywee\CMSBundle\Service;


use Dywee\CMSBundle\Entity\CustomForm;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactory;

class CustomFormBuilder
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * CustomFormBuilder constructor.
     *
     * @param FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param CustomForm $customForm
     *
     * @return mixed
     */
    public function buildForm(CustomForm $customForm)
    {
        $fb = $this->formFactory->createBuilder();

        $index = 1;
        foreach ($customForm->getFields() as $field) {
            $options = [];

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
            $options['attr'] = [
                'placeholder' => $field->getPlaceholder(),
            ];

            $fb->add($field->getId(), $type, $options);

            $index++;
        }

        $fb->add($customForm->getSumbitButtonValue(), SubmitType::class);

        return $fb->getForm();
    }
}
