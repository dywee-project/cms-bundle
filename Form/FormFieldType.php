<?php

namespace Dywee\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormFieldType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add('type', ChoiceType::class, array(
                'label' => 'Type du champ',
                'choices' => array(
                    'text' => 'Champ de texte',
                    'textarea' => 'Zone de texte',
                    'email' => 'Champ pour email',
                    'url' => 'Champ pour url',
                    'number' => 'Champ pour un nombre',
                    'select' => 'Liste déroulante',
                    'checkbox' => 'Case à cocher',
                    'radio' => 'Bouton radio'
                )
            ))
            ->add('required', CheckboxType::class, array('required' => false))
            ->add('possibleValuesText', TextType::class, array('required' => false))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dywee\CMSBundle\Entity\FormField'
        ));
    }
}
