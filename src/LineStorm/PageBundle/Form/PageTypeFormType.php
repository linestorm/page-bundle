<?php

namespace LineStorm\PageBundle\Form;

use LineStorm\CmsBundle\Form\AbstractCmsFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageTypeFormType extends AbstractCmsFormType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', 'text', array(
                'required' => true
            ))
            ->add('controller', 'text', array(
                'required' => true
            ))
            ->add('action', 'text', array(
                'required' => true
            ))
            ->add('template', 'text', array(
                'required' => true
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->modelManager->getEntityClass('page_type')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'linestorm_cms_form_page_type';
    }
}
