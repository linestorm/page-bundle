<?php

namespace LineStorm\PageBundle\Form;

use LineStorm\CmsBundle\Form\AbstractCmsFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageFormType extends AbstractCmsFormType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'required' => true
            ))
            ->add('coverImage', 'mediaentity', array(

            ))
            ->add('liveOn', 'datetime', array(
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'empty_data'  => new \DateTime(),
            ))
            ->add('slug')
            ->add('blurb')
            ->add('metaDescription')
            ->add('metaKeywords')
        ;

        $module = $this->moduleManager->getModule('page');
        foreach($module->getComponents() as $component){
            $component->buildForm($builder, $options);
        }

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->modelManager->getEntityClass('page')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'linestorm_cms_form_page';
    }
}
