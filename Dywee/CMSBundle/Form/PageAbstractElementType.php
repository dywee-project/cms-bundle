<?php

namespace Dywee\CMSBundle\Form;

use Dywee\CoreBundle\Form\Type\SeoType;
use Dywee\CMSBundle\Form\PageElementType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageAbstractElementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type',               'number')
            ->add('content',            'text')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dywee_cmsbundle_pageabstractelement';
    }
}
