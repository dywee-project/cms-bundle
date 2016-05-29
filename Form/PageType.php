<?php

namespace Dywee\CMSBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type',               ChoiceType::class, array(
                'choices' => array(
                    1 => 'Index',
                    2 => 'Page',
                    3 => 'Contact',
                    4 => 'News',
                    5 => 'Calendrier',
                    6 => 'Magasin',
                    7 => 'Blog',
                    8 => 'Formulaire',
                    9 => 'FAQ',
                    12 => 'Musique'
                )
            ))
            ->add('metaTitle',          null,     array('required' => false))
            ->add('metaDescription',    TextareaType::class, array('required' => false))
            ->add('metaKeywords',       TextareaType::class, array('required' => false))
            ->add('seoUrl',             null,     array('required' => false))
            ->add('menuName',           null,     array('required' => false))
            ->add('inMenu',             CheckboxType::class, array('required' => false))
            ->add('menuOrder',          IntegerType::class,   array('required' => false))
            ->add('childArguments',     TextType::class, array('required' => false))
            ->add('state',              ChoiceType::class,       array('choices' => array(0 => 'Brouillon', 1 => 'Publiée')))
            ->add('sauvegarder',        SubmitType::class)
            ->add('parent',             EntityType::class,   array(
                'class'     => 'DyweeCMSBundle:Page',
                'choice_label'  => 'menuName',
                'required'  => false,
            ))
            ->add('pageElements',         CollectionType::class,      array(
                'entry_type' => PageElementType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('cheatingTrick',  CKEditorType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dywee\CMSBundle\Entity\Page',
        ));
    }
}
