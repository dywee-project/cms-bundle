<?php

namespace Dywee\CMSBundle\Form;

use Dywee\CoreBundle\Form\Type\SeoType;
use Symfony\Component\Form\AbstractType;
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
            ->add('name',               'text')
            ->add('type',               'choice', array(
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
                    10 => 'Musique'
                )
            ))
            ->add('content',            'ckeditor')
            ->add('seo',                new SeoType(),      array(
                'data_class' => 'Dywee\CMSBundle\Entity\Page'
            ))
            ->add('menuName',           'text',     array('required' => false))
            ->add('inMenu',             'checkbox', array('required' => false))
            ->add('menuOrder',          'integer',   array('required' => false))
            ->add('parent',             'entity',   array(
                'class'     => 'DyweeCMSBundle:Page',
                'property'  => 'menuName',
                'required'  => false
            ))
            ->add('childArguments', 'text', array('required' => false))
            ->add('state',              'choice',       array('choices' => array(0 => 'Brouillon', 1 => 'Publiée')))
            ->add('sauvegarder',        'submit')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dywee\CMSBundle\Entity\Page'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dywee_cmsbundle_page';
    }
}
