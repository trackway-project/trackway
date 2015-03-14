<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * Class ProfileFormType
 *
 * @package AppBundle\Form\Type
 */
class ProfileFormType extends AbstractOverridableFormType
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
                'choices' => [
                    null => 'locale.default',
                    'de' => 'locale.de',
                    'en' => 'locale.en'
                ],
                'required' => false
            ], $options))
            ->add('memberships', 'entity', $this->overrideOptions('memberships', [
                'expanded'  => true,
                'multiple'  => true,
                'class' => 'AppBundle\Entity\Membership'
            ], $options))
            ->add('activeTeam', 'entity', $this->overrideOptions('activeTeam', [
                'class' => 'AppBundle\Entity\Team'
            ], $options))
            ->add('current_password', 'password', $this->overrideOptions('current_password', [
                'mapped' => false,
                'constraints' => new UserPassword()
            ], $options));
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
        return 'appbundle_profile_form_type';
    }
}