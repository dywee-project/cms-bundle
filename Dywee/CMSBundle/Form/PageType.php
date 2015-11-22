<?php

namespace Dywee\CMSBundle\Form;

use Dywee\CoreBundle\Form\Type\SeoType;
use Dywee\CMSBundle\Form\PageElementType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Dywee\CMSBundle\Entity\PageRepository;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $website = $builder->getData()->getWebsite();

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
                    12 => 'Musique'
                )
            ))
            ->add('seo',                new SeoType(),      array(
                'data_class' => 'Dywee\CMSBundle\Entity\Page'
            ))
            ->add('menuName',           'text',     array('required' => false))
            ->add('inMenu',             'checkbox', array('required' => false))
            ->add('menuOrder',          'integer',   array('required' => false))
            ->add('childArguments', 'text', array('required' => false))
            ->add('state',              'choice',       array('choices' => array(0 => 'Brouillon', 1 => 'Publiée')))
            ->add('sauvegarder',        'submit')
            ->add('parent',             'entity',   array(
                'class'     => 'DyweeCMSBundle:Page',
                'property'  => 'menuName',
                'required'  => false,
                'query_builder' => function(PageRepository $er) use ($website){
                    return $er->createQueryBuilder('p')->select('p')->where('p.inMenu = 1 and p.website = :id')->setParameter('id', $website);
                },
            ))
            ->add('pageElements', 'collection', array(
                'type' => new PageElementType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('cheatingTrick',  'ckeditor')
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

    /**
     * @return string
     */
    public function getName()
    {
        return 'dywee_cmsbundle_page';
    }
}
