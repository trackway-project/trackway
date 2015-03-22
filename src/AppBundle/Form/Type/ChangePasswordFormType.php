<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * Class ChangePasswordFormType
 *
 * @package AppBundle\Form\Type
 */
class ChangePasswordFormType extends AbstractOverridableFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password',
            'repeated',
            $this->overrideOptions('password',
                ['type' => 'password',
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options' => ['label' => 'user.entity.password'],
                    'second_options' => ['label' => 'user.entity.passwordRepeat']],
                $options))
            ->add('currentPassword',
                'password',
                $this->overrideOptions('currentPassword',
                    ['label' => 'user.entity.passwordCurrent', 'mapped' => false, 'required' => true, 'constraints' => new UserPassword()],
                    $options));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\User', 'override' => false, 'validation_groups' => ['change_password']]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_change_password_form_type';
    }
}
