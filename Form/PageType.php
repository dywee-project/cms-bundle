<?php

namespace Dywee\CMSBundle\Form;

use Dywee\CMSBundle\Entity\Page;
use Dywee\CoreBundle\Form\Type\SeoType;
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
        $stateChoices = array();
        $typeChoices = array();

        foreach(Page::getConstantList() as $constant)
            if(strstr($constant, 'state.'))
                $stateChoices[$constant] = strtolower($constant);
            elseif(strstr($constant, 'type.'))
                $typeChoices[$constant] = strtolower($constant);

        $builder
            ->add('name')
            ->add('type',               ChoiceType::class, array(
                'choices' => $typeChoices
            ))
            ->add('seo',                SeoType::class,         array(
                'data_class' => 'Dywee\ProductBundle\Entity\BaseProduct'
            ))
            ->add('menuName',           null,     array('required' => false))
            ->add('inMenu',             CheckboxType::class, array('required' => false))
            ->add('menuOrder',          IntegerType::class,   array('required' => false))
            ->add('childArguments',     TextType::class, array('required' => false))
            ->add('state',              ChoiceType::class,       array('choices' => $stateChoices))
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
            ->add('cheatingTrick',  CKEditorType::class, array('required' => false))
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
