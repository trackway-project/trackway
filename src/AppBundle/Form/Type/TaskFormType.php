<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TaskFormType
 *
 * @package AppBundle\Form\Type
 */
class TaskFormType extends AbstractOverrideType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, $this->overrideOptions('name', [
                'required' => true,
                'trim' => true
            ], $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Task',
            'override' => false
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_task_form_type';
    }
}
