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
        $builder->add('email', 'email', $this->overrideOptions('email', ['label' => 'user.entity.email', 'required' => true], $options))
            ->add('locale', 'entity', $this->overrideOptions('locale', ['label' => 'user.entity.locale', 'class' => 'AppBundle\Entity\Locale'], $options))
            ->add('activeTeam',
                'entity',
                $this->overrideOptions('activeTeam', ['label' => 'user.entity.activeTeam', 'class' => 'AppBundle\Entity\Team'], $options))
            ->add('currentPassword',
                'password',
                $this->overrideOptions('currentPassword',
                    ['label' => 'user.entity.currentPassword', 'mapped' => false, 'required' => true, 'constraints' => new UserPassword()],
                    $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\User', 'override' => false, 'validation_groups' => ['profile']]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_profile_form_type';
    }
}
