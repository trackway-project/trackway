<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MembershipFormType extends AbstractOverrideType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $overrideOptions = array_key_exists('override', $options) && is_array($options['override']) ? $options['override'] : [];

        $builder->add('team')->add('user')->add('group');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Membership', 'override' => false]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_membership_form_type';
    }
}
