<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MembershipFormType
 * @package AppBundle\Form\Type
 */
class MembershipFormType extends AbstractOverridableFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('team', 'entity', $this->overrideOptions('team', ['class' => 'AppBundle\Entity\Team', 'expanded' => true, 'required' => true], $options))->add('user', 'entity', $this->overrideOptions('user', ['class' => 'AppBundle\Entity\User', 'expanded' => true, 'required' => true], $options))->add('group', 'entity', $this->overrideOptions('group', ['class' => 'AppBundle\Entity\Group', 'expanded' => true, 'required' => true], $options));
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
