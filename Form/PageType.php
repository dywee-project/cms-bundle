<?php

namespace Dywee\CMSBundle\Form;

use Dywee\CMSBundle\Entity\Page;
use Dywee\CoreBundle\Form\Type\SeoType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $stateChoices = [];
        $typeChoices = [];

        foreach (Page::getConstantList() as $constant) {
            if (false !== strpos($constant, 'state.')) {
                $stateChoices[$constant] = strtolower($constant);
            } elseif (false !== strpos($constant, 'type.')) {
                $typeChoices[$constant] = strtolower($constant);
            }
        }

        $builder
            ->add('name')
            ->add('type', ChoiceType::class, [
                'choices' => $typeChoices,
            ])
            ->add('seo', SeoType::class, [
                'data_class' => 'Dywee\ProductBundle\Entity\BaseProduct',
            ])
            ->add('menuName', null, ['required' => false])
            ->add('inMenu', CheckboxType::class, ['required' => false])
            ->add('menuOrder', IntegerType::class, ['required' => false])
            ->add('childArguments', TextType::class, ['required' => false])
            ->add('state', ChoiceType::class, ['choices' => $stateChoices])
            ->add('parent', EntityType::class, [
                'class'        => 'DyweeCMSBundle:Page',
                'choice_label' => 'menuName',
                'required'     => false,
            ])
            ->add('pageElements', CollectionType::class, [
                'entry_type'   => PageElementType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('cheatingTrick', CKEditorType::class, ['required' => false]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Dywee\CMSBundle\Entity\Page',
        ]);
    }
}
