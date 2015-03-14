<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ResettingConfirmFormType
 *
 * @package AppBundle\Form\Type
 */
class ResettingConfirmFormType extends AbstractOverridableFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', 'repeated', $this->overrideOptions('plainPassword', [
                'type' => 'password'
            ], $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'override' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_resetting_confirm_form_type';
    }
}
