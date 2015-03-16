<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UserFormType
 *
 * @package AppBundle\Form\Type
 */
class UserFormType extends AbstractOverridableFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, $this->overrideOptions('username', [], $options))
            ->add('email', 'email', $this->overrideOptions('email', [], $options))
            ->add('locale', 'choice', $this->overrideOptions('locale', [
                'class' => 'AppBundle\Entity\Locale'
            ], $options))
            ->add('memberships', 'entity', $this->overrideOptions('memberships', [
                'expanded'  => true,
                'multiple'  => true,
                'class' => 'AppBundle\Entity\Membership'
            ], $options))
            ->add('activeTeam', 'entity', $this->overrideOptions('activeTeam', [
                'class' => 'AppBundle\Entity\Team'
            ], $options))
            ->add('enabled', 'checkbox', $this->overrideOptions('enabled', [], $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
            'override' => false
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_user_form_type';
    }
}