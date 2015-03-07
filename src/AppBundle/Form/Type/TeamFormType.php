<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeamFormType extends AbstractOverrideType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $overrideOptions = array_key_exists('override', $options) && is_array($options['override']) ? $options['override'] : [];

        $builder->add('name', null, ['trim' => true])->add('memberships');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Team', 'override' => false]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_team_form_type';
    }
}
