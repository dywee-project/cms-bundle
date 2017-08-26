<?php

namespace Dywee\CMSBundle\Form;

use Dywee\CMSBundle\Entity\FormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormFieldType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add(
                'type',
                ChoiceType::class,
                [
                    'label'   => 'Type du champ',
                    'choices' => [
                        'field.type.text'      => TextType::class,
                        'field.type.textarea'  => TextareaType::class,
                        'field.type.email'     => EmailType::class,
                        'field.type.url'       => UrlType::class,
                        'field.type.number'    => NumberType::class,
                        'field.type.select'    => CollectionType::class,
                        'field.type.checkbox ' => CollectionType::class,
                        'field.type.radio'     => CollectionType::class,
                    ],
                ]
            )
            ->add('required', CheckboxType::class, ['required' => false])
            ->add('possibleValuesText', TextType::class, ['required' => false]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => FormField::class,
            ]
        );
    }
}
